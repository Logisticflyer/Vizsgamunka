<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;
use app\models\Order3d;

class Ai3dController extends Controller
{
    /**
     * Csak JSON válaszokat küldünk vissza az API hívásokra
     */
    public function beforeAction($action)
    {
        if (in_array($action->id, ['process-chat', 'upload-attachment', 'submit-order'])) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $this->enableCsrfValidation = false; // API hívásokhoz
        }
        return parent::beforeAction($action);
    }

    /**
     * Vásárlói főoldal / AI Tervező nézet
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * 1. FÁJL & KÉP CSATOLÁSA A CHATBEN
     */
    public function actionUploadAttachment()
    {
        $uploadedFile = UploadedFile::getInstanceByName('attachment');

        if ($uploadedFile) {
            $uploadDir = Yii::getAlias('@webroot/uploads/attachments/');
            FileHelper::createDirectory($uploadDir);

            $fileName = time() . '_' . uniqid() . '.' . $uploadedFile->extension;
            $filePath = $uploadDir . $fileName;

            if ($uploadedFile->saveAs($filePath)) {
                return [
                    'success' => true,
                    'file_path' => 'uploads/attachments/' . $fileName,
                    'original_name' => $uploadedFile->baseName . '.' . $uploadedFile->extension,
                ];
            }
        }

        return ['success' => false, 'error' => 'Nem sikerült a fájl feltöltése.'];
    }

    /**
     * 🤖 DEDIKÁLT GEMINI API ENGINE A 3D CAD TERVEZŐHÖZ
     */
    public function actionProcessChat()
    {
        $request = Yii::$app->request;
        $userMessage = $request->post('message', '');
        $attachmentPath = $request->post('attachment_path', null);
        $prevParams = json_decode($request->post('current_parameters', '{}'), true);

        // 1. KULCS BEOLVASÁSA
        $rawApiKey = Yii::$app->params['cadGeminiApiKey'] 
            ?? Yii::$app->params['geminiApiKey'] 
            ?? null;

        if (!$rawApiKey) {
            $rawApiKey = $_ENV['GEMINI_API_KEY'] ?? $_SERVER['GEMINI_API_KEY'] ?? null;
        }

        if ($rawApiKey) {
            // Kulcs tisztítása
            $cleanApiKey = trim($rawApiKey);
            $cleanApiKey = preg_replace('/^Bearer\s+/i', '', $cleanApiKey);
            $cleanApiKey = trim($cleanApiKey, " \t\n\r\0\x0B\"'");

            // 2. SYSTEM INSTRUCTION (Frissítve a BOM adatok kényszerítésével)
            $systemInstruction = "A neved '3D CAD AI Mérnök Asszisztens'. Egy profi ipari 3D CAD tervező szoftver backendje vagy.
A feladatod a felhasználó természetes nyelven megadott kéréseiből kiszámítani a 3D gépészeti parametrikus adatokat, a tömeget (kg) és a gyártási költséget (Ft).

SZIGORÚ SZABÁLYOK:
1. Válaszolj MINDIG érvényes JSON formátumban!
2. A JSON kimenetben KÖTELEZŐEN szerepeljen egy 'ai_reply' kulcs, amiben röviden, magyarul elmagyarázod a felhasználónak, hogy mit terveztél meg neki!
3. A 'dimensions.height' és 'dimensions.width' méterben értendő! (Ha a felhasználó mm-ben adja meg pl. 4000mm, váltsd át 4.0-ra!).
4. Az 'assembly_tree' tartalmazza a gép 3D alkotóelemeit:
   - Típusok lehetnek: 'truncated_cone' (garat), 'cylinder_horizontal' (csigaház), 'motor_block' (motor), 'frame_legs' (tartóváz), 'box', 'cylinder'.
   - 'explode_dir' [X, Y, Z] adja meg a robbantott nézeti elmozdulási irányt.
5. Ha a felhasználó etetőt/adagolót kér, 4 komponenst építs fel (Garat, Csigaház, Motor, Váz).
6. Kiszámított tömegek (weight_kg) és árak (price_huf) reálisak legyenek!
7. FONTOS BOM ADATOK: Az 'assembly_tree' minden egyes eleme (node) kötelezően tartalmazza ezeket a kulcsokat is: 'material' (szöveg, pl. 'Rozsdamentes Acél'), 'weight' (szám, kg-ban), 'price' (szám, Ft-ban)!";

            // 3. PROMPT ELŐKÉSZÍTÉSE
            $promptText = "Felhasználó üzenete: '$userMessage'.";
            if ($attachmentPath) {
                $promptText .= " Csatolt fájl elérési útja: '$attachmentPath'. Rendeld hozzá a paraméterekhez!";
            }
            if (!empty($prevParams)) {
                $promptText .= " Előző beállított paraméterek: " . json_encode($prevParams, JSON_UNESCAPED_UNICODE);
            }

            $payload = [
                'system_instruction' => [
                    'parts' => [['text' => $systemInstruction]]
                ],
                'contents' => [
                    [
                        'role' => 'user',
                        'parts' => [['text' => $promptText]]
                    ]
                ],
                'generationConfig' => [
                    'response_mime_type' => 'application/json',
                    'temperature' => 0.2
                ]
            ];

            $jsonPayload = json_encode($payload, JSON_UNESCAPED_UNICODE);

            // 4. MODEL FALLBACK CIKLUS
            $modelsToTry = [
                'gemini-1.5-flash',
                'gemini-2.0-flash',
                'gemini-2.5-flash'
            ];

            $lastError = '';

            foreach ($modelsToTry as $modelName) {
                $apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/{$modelName}:generateContent?key=" . $cleanApiKey;

                $ch = curl_init($apiUrl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonPayload);
                curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json; charset=utf-8']);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
                curl_setopt($ch, CURLOPT_TIMEOUT, 35);

                $response = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                $error = curl_error($ch);
                curl_close($ch);

                if (!$error && $httpCode === 200) {
                    $responseData = json_decode($response, true);
                    $rawJson = $responseData['candidates'][0]['content']['parts'][0]['text'] ?? '{}';
                    
                    // MARKDOWN KÓDBLOKK TISZTÍTÁSA (```json és ``` eltávolítása)
                    $cleanJson = preg_replace('/^```(?:json)?\s*|\s*```$/i', '', trim($rawJson));
                    $parsedAiData = json_decode($cleanJson, true);

                    if ($parsedAiData && is_array($parsedAiData)) {
                        $aiText = $parsedAiData['ai_reply'] 
                            ?? $parsedAiData['reply'] 
                            ?? $parsedAiData['message'] 
                            ?? $parsedAiData['description'] 
                            ?? "A 3D modell és a paraméterek sikeresen elkészültek!";

                        return [
                            'success' => true,
                            'reply' => $aiText,
                            'ai_reply' => $aiText,
                            'message' => $aiText,
                            'response' => $aiText,
                            'shape_data' => [
                                'assembly_type' => $parsedAiData['assembly_type'] ?? 'feeder_system',
                                'assembly_tree' => $parsedAiData['assembly_tree'] ?? [],
                                'total_weight' => $parsedAiData['total_weight'] ?? 0,
                                'total_price' => $parsedAiData['total_price'] ?? 0,
                                'dimensions' => $parsedAiData['dimensions'] ?? ['height' => 2.5, 'width' => 1.5],
                            ],
                            'warnings' => [],
                        ];
                    } else {
                        $lastError = "A Gemini válaszolt, de a JSON dekódolás sikertelen volt.";
                    }
                }

                if (in_array($httpCode, [404, 429])) {
                    $lastError = "Modell hiba vagy API Rate Limit (Kód: $httpCode). Próbálkozás a következő modellel...";
                    continue;
                }

                $lastError = "Váratlan API hiba (Kód: $httpCode). Hálózat: $error";
                break;
            }
            
            // Ha idáig eljutott a kód, akkor az API hívások mind elbuktak.
            Yii::error("3D AI Tervező API Hiba: " . $lastError, 'ai_cad');
        } else {
            Yii::error("3D AI Tervező Hiba: Nincs beállítva API kulcs.", 'ai_cad');
        }

        // 🛡️ OFFLINE FALLBACK ENGINE: Ha az API nem elérhető, biztonsági válasz adása
        return $this->fallbackMockEngine($userMessage, $prevParams);
    }

    /**
     * 🛡️ BIZTONSÁGI TARTALÉK MOTOR (OFFLINE / FALLBACK)
     * Kibővítve BOM adatokkal (material, weight, price), hogy a frontend ne írjon undefined-et.
     */
    /**
     * 🛡️ BIZTONSÁGI TARTALÉK MOTOR (OFFLINE / FALLBACK)
     */
    private function fallbackMockEngine($userMessage, $prevParams = [])
    {
        $replyText = "⚠️ AI API Hálózat túlterhelt. Offline generátor aktiválva: A 3D modellt sikeresen elkészítettem az alapértelmezett ipari paraméterekkel!";

        return [
            'success' => true,
            'reply' => $replyText,
            'ai_reply' => $replyText,
            'message' => $replyText,
            'response' => $replyText,
            'shape_data' => [
                'assembly_type' => 'feeder_system',
                'assembly_tree' => [
                    [
                        'name' => 'Garat',
                        'type' => 'truncated_cone',
                        'dimensions' => ['top_radius' => 0.8, 'bottom_radius' => 0.2, 'height' => 1.0],
                        'position' => [0, 1.5, 0],
                        'color' => '#3498db',
                        'material' => 'Rozsdamentes Acél (304)',
                        'weight' => 150,
                        'weight_kg' => 150, // Biztosíték a JS-nek
                        'kg' => 150,        // Biztosíték a JS-nek
                        'price' => 450000,
                        'price_huf' => 450000 // Biztosíték a JS-nek
                    ],
                    [
                        'name' => 'Csigaház',
                        'type' => 'cylinder_horizontal',
                        'dimensions' => ['radius' => 0.25, 'length' => 2.0],
                        'position' => [0, 0.5, 0],
                        'color' => '#95a5a6',
                        'material' => 'Szerkezeti Acél (S235)',
                        'weight' => 300,
                        'weight_kg' => 300, // Biztosíték a JS-nek
                        'kg' => 300,        // Biztosíték a JS-nek
                        'price' => 800000,
                        'price_huf' => 800000 // Biztosíték a JS-nek
                    ]
                ],
                'total_weight' => 450,
                'total_price' => 1250000,
                'dimensions' => ['height' => 3.0, 'width' => 2.0],
            ],
            'warnings' => [],
        ];
    }

    /**
     * 3. VÉGLEGES 3D TERV ÉS MEGRENDELÉS BEKÜLDÉSE AZ ADMINNAK
     */
    public function actionSubmitOrder()
    {
        $request = Yii::$app->request;

        $order = new Order3d();
        $order->user_id = Yii::$app->user->id ?? null;
        $order->title = $request->post('title', 'Új 3D AI Koncepció');
        $order->description = $request->post('description');
        $order->parameters = $request->post('parameters');
        $order->attachments = $request->post('attachments');
        $order->chat_history = $request->post('chat_history');
        $order->concept_image = $request->post('concept_image');
        $order->model_file = $request->post('model_file');
        $order->status = 'pending';

        if ($order->save()) {
            return [
                'success' => true,
                'order_id' => $order->id,
                'message' => 'A 3D megrendelés sikeresen továbbítva az adminisztrátornak!',
            ];
        }

        return [
            'success' => false,
            'errors' => $order->getErrors(),
        ];
    }
}