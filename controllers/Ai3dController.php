<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;
use yii\helpers\Html;
use app\models\Order3d;

class Ai3dController extends Controller
{
    /**
     * JSON válaszformátum és CSRF szabályzat egységesítése az API végpontokhoz
     */
    public function beforeAction($action)
    {
        $jsonActions = [
            'process-chat', 
            'upload-attachment', 
            'submit-order', 
            'submit-quote', 
            'get-templates', 
            'get-materials'
        ];

        if (in_array($action->id, $jsonActions)) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionLanding()
    {
        return $this->render('landing');
    }

    /**
     * 1. BIZTONSÁGOS FÁJL & KÉP CSATOLÁS
     */
    public function actionUploadAttachment()
    {
        $uploadedFile = UploadedFile::getInstanceByName('attachment');

        if ($uploadedFile) {
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf', 'webp'];
            $ext = strtolower($uploadedFile->extension);

            if (!in_array($ext, $allowedExtensions)) {
                return ['success' => false, 'error' => 'Érvénytelen fájltípus! Csak JPG, PNG, WEBP és PDF engedélyezett.'];
            }

            if ($uploadedFile->size > 10 * 1024 * 1024) { // 10 MB limit
                return ['success' => false, 'error' => 'A fájl mérete nem haladhatja meg a 10 MB-ot!'];
            }

            $uploadDir = Yii::getAlias('@webroot/uploads/attachments/');
            FileHelper::createDirectory($uploadDir);

            $fileName = time() . '_' . Yii::$app->security->generateRandomString(8) . '.' . $ext;
            $filePath = $uploadDir . $fileName;

            if ($uploadedFile->saveAs($filePath)) {
                return [
                    'success' => true,
                    'file_path' => 'uploads/attachments/' . $fileName,
                    'original_name' => Html::encode($uploadedFile->baseName . '.' . $ext),
                ];
            }
        }

        return ['success' => false, 'error' => 'Nem sikerült a fájl feltöltése.'];
    }

    /**
     *  DEDIKÁLT GEMINI API ENGINE A 3D CAD TERVEZŐHÖZ ÉS CHATHEZ
     */
    public function actionProcessChat()
    {
        $request = Yii::$app->request;
        $userMessage = $request->post('message', '');
        $attachmentPath = $request->post('attachment_path', null);
        $prevParams = json_decode($request->post('current_parameters', '{}'), true);

        // 1. KULCS BEOLVASÁSA ÉS TISZTÍTÁSA
        $rawApiKey = Yii::$app->params['cadGeminiApiKey'] 
            ?? Yii::$app->params['geminiApiKey'] 
            ?? $_ENV['GEMINI_API_KEY'] 
            ?? $_SERVER['GEMINI_API_KEY'] 
            ?? null;

        if ($rawApiKey) {
            $cleanApiKey = trim(preg_replace('/^Bearer\s+/i', '', $rawApiKey), " \t\n\r\0\x0B\"'");

            date_default_timezone_set('Europe/Budapest');
            $daysHu = ['Vasárnap', 'Hétfő', 'Kedd', 'Szerda', 'Csütörtök', 'Péntek', 'Szombat'];
            $now = new \DateTime();

            $currentDate = $now->format('Y-m-d');
            $currentTime = $now->format('H:i:s');
            $currentDayName = $daysHu[(int)$now->format('w')];

            $systemInstruction = "A neved '3D CAD AI Mérnök Asszisztens'. Egy profi ipari 3D CAD tervező szoftver backendje vagy.

JELENLEGI PONTOS SYSTEM DÁTUM ÉS IDŐ: {$currentDate} {$currentTime} ({$currentDayName}).

SZIGORÚ NAPTÁR- ÉS IDŐ-SZABÁLYOK:
- Ha a felhasználó relatív időpontra vagy dátumra kérdez (pl. 'holnap', 'tegnap', 'jövő hét szerda', 'következő hónap', 'mikor lesz...'), KIZÁRÓLAG a fenti system dátumot ({$currentDate}) és napot ({$currentDayName}) vedd alapul a pontos matematikai naptári kiszámításhoz!
- Soha ne találj ki tetszőleges dátumokat, éveket vagy hónapokat!

SZIGORÚ 3D CAD & ADATSTRUKTÚRA SZABÁLYOK:
1. Válaszolj MINDIG érvényes JSON formátumban!
2. A JSON kimenetben KÖTELEZŐEN szerepeljen egy 'ai_reply' kulcs, amiben röviden, magyarul elmagyarázod a válaszodat a felhasználónak!
3. A 'dimensions.height' és 'dimensions.width' méterben értendő! (Ha a felhasználó mm-ben adja meg pl. 4000mm, váltsd át 4.0-ra!).
4. Az 'assembly_tree' tartalmazza a gép 3D alkotóelemeit:
   - Típusok lehetnek: 'truncated_cone' (garat), 'cylinder_horizontal' (csigaház), 'motor_block' (motor), 'frame_legs' (tartóváz), 'conveyor_belt', 'box', 'cylinder'.
   - 'explode_dir' [X, Y, Z] adja meg a robbantott nézeti elmozdulási irányt.
5. Ha a felhasználó etetőt/adagolót kér, 4 komponenst építs fel (Garat, Csigaház, Motor, Váz).
6. Kiszámított tömegek (weight_kg) és árak (price_huf) reálisak legyenek!
7. FONTOS BOM ADATOK: Az 'assembly_tree' minden egyes eleme (node) kötelezően tartalmazza ezeket a kulcsokat is: 'material' (szöveg, pl. 'Rozsdamentes Acél'), 'weight_kg' (szám, kg-ban), 'price_huf' (szám, Ft-ban), 'color_hex' (pl. '#cbd5e1')!";

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
                    'temperature' => 0.1
                ]
            ];

            $modelsToTry = [
                'gemini-2.0-flash',
                'gemini-1.5-flash',
                'gemini-1.5-pro'
            ];

            $lastError = '';

            foreach ($modelsToTry as $modelName) {
                $apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/{$modelName}:generateContent?key=" . $cleanApiKey;

                $ch = curl_init($apiUrl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload, JSON_UNESCAPED_UNICODE));
                curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json; charset=utf-8']);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
                curl_setopt($ch, CURLOPT_TIMEOUT, 35);

                $response = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                $error = curl_error($ch);
                curl_close($ch);

                if (!$error && $httpCode === 200) {
                    $responseData = json_decode($response, true);
                    $rawJson = $responseData['candidates'][0]['content']['parts'][0]['text'] ?? '{}';
                    
                    $cleanJson = preg_replace('/^```(?:json)?\s*|\s*```$/i', '', trim($rawJson));
                    $parsedAiData = json_decode($cleanJson, true);

                    if ($parsedAiData && is_array($parsedAiData)) {
                        $aiText = $parsedAiData['ai_reply'] 
                            ?? $parsedAiData['reply'] 
                            ?? $parsedAiData['message'] 
                            ?? $parsedAiData['description'] 
                            ?? "A válasz és a 3D modell sikeresen feldolgozva!";

                        $totalPrice = (float)($parsedAiData['total_price'] ?? 540000);

                        return [
                            'success' => true,
                            'reply' => $aiText,
                            'ai_reply' => $aiText,
                            'message' => $aiText,
                            'response' => $aiText,
                            'quote_complete' => true,
                            'quote_data' => [
                                'project_title' => $parsedAiData['assembly_type'] ?? 'Egyedi Gépészeti Modul',
                                'dimensions' => $parsedAiData['dimensions'] ?? ['height' => 2.5, 'width' => 1.5],
                                'material' => 'Rozsdamentes Acél (1.4301)',
                                'quantity' => 1,
                                'lead_time_days' => 12,
                                'estimated_price_huf' => $totalPrice,
                                'breakdown' => [
                                    ['name' => 'Főszerkezet & Megmunkálás', 'cost' => (int)round($totalPrice * 0.6)],
                                    ['name' => 'Szerelvények & DFM Ellenőrzés', 'cost' => (int)round($totalPrice * 0.4)],
                                ],
                                'assembly_tree' => $parsedAiData['assembly_tree'] ?? []
                            ],
                            'shape_data' => [
                                'assembly_type' => $parsedAiData['assembly_type'] ?? 'feeder_system',
                                'assembly_tree' => $parsedAiData['assembly_tree'] ?? [],
                                'total_weight' => $parsedAiData['total_weight'] ?? 0,
                                'total_price' => $totalPrice,
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
            
            Yii::error("3D AI Tervező API Hiba: " . $lastError, 'ai_cad');
        } else {
            Yii::error("3D AI Tervező Hiba: Nincs beállítva API kulcs.", 'ai_cad');
        }

        return $this->fallbackMockEngine($userMessage, $prevParams);
    }

    /**
     * 🛡️ OFFLINE TARTALÉK MOTOR
     */
    private function fallbackMockEngine($userMessage, $prevParams = [])
    {
        $replyText = "⚠️ AI API Hálózat nem elérhető. Offline generátor aktiválva: Az alapértelmezett ipari gépészeti modellt betöltöttük!";

        $assemblyTree = [
            [
                'name' => 'Adagoló Garat',
                'type' => 'hopper',
                'material' => 'Rozsdamentes Acél (304)',
                'weight_kg' => 24.0,
                'price_huf' => 160000,
                'color_hex' => '#cbd5e1',
                'position' => [0, 1.2, 0],
                'explode_dir' => [0, 1, 0],
                'params' => ['rTop' => 1.2, 'rBottom' => 0.4, 'height' => 1.5]
            ],
            [
                'name' => 'Szállítószálag Váz',
                'type' => 'conveyor_belt',
                'material' => 'S235 Acél',
                'weight_kg' => 35.0,
                'price_huf' => 210000,
                'color_hex' => '#95a5a6',
                'position' => [0, 0.4, 0],
                'explode_dir' => [0, 0.5, 0],
                'params' => ['length' => 2.8, 'width' => 0.8]
            ],
            [
                'name' => 'Ipari Hajtómotor',
                'type' => 'motor_block',
                'material' => 'Öntöttvas',
                'weight_kg' => 18.5,
                'price_huf' => 120000,
                'color_hex' => '#2563eb',
                'position' => [1.2, 0.4, 0],
                'explode_dir' => [1, 0, 0],
                'params' => ['power_kw' => 2.2]
            ],
            [
                'name' => 'Tartóláb Szerkezet',
                'type' => 'frame_legs',
                'material' => 'S235 Acél',
                'weight_kg' => 12.0,
                'price_huf' => 50000,
                'color_hex' => '#475569',
                'position' => [0, -0.6, 0],
                'explode_dir' => [0, -1, 0],
                'params' => ['height' => 1.2, 'width' => 1.0, 'length' => 2.2]
            ]
        ];

        return [
            'success' => true,
            'reply' => $replyText,
            'ai_reply' => $replyText,
            'quote_complete' => true,
            'shape_data' => [
                'assembly_type' => 'feeder_system',
                'assembly_tree' => $assemblyTree,
                'total_weight' => 89.5,
                'total_price' => 540000,
                'dimensions' => ['height' => 2.5, 'width' => 1.5],
            ],
            'warnings' => [],
        ];
    }

    /**
     * 3D Árajánlatkérés elmentése a központi ajanlatkeres táblába
     */
    public function actionSubmitQuote()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $request = Yii::$app->request;
        $userName = trim($request->post('user_name', ''));
        $userEmail = trim($request->post('user_email', ''));
        $userPhone = trim($request->post('user_phone', ''));
        $companyName = trim($request->post('company_name', ''));
        $projectTitle = trim($request->post('project_title', '3D CAD / Árajánlat'));
        
        $quoteDataJson = $request->post('quote_data');
        $shapeParamsJson = $request->post('shape_parameters');
        $totalPrice = $request->post('total_price_huf', 0);

        if (empty($userName) || empty($userEmail)) {
            return ['success' => false, 'message' => 'Név és e-mail cím megadása kötelező!'];
        }

        try {
            $quoteData = json_decode($quoteDataJson, true) ?? [];
            $shapeParams = json_decode($shapeParamsJson, true) ?? [];

            // Részletes projektleírás 
            $leirasDetails = "Becsült Ár: " . number_format((float)$totalPrice, 0, ',', ' ') . " Ft\n";
            $leirasDetails .= "Alapanyag: " . ($quoteData['material'] ?? '-') . "\n";
            $leirasDetails .= "Darabszám: " . ($quoteData['quantity'] ?? 1) . " db\n";
            $leirasDetails .= "Méretek (L x W x H): " . ($shapeParams['length_mm'] ?? '-') . "x" . ($shapeParams['width_mm'] ?? '-') . "x" . ($shapeParams['height_mm'] ?? '-') . " mm\n";
            if (!empty($userPhone)) {
                $leirasDetails .= "Telefonszám: " . $userPhone . "\n";
            }
            if (!empty($companyName)) {
                $leirasDetails .= "Cégnév: " . $companyName . "\n";
            }
            if (!empty($quoteData['description'])) {
                $leirasDetails .= "Egyedi leírás: " . $quoteData['description'];
            }

            // Mentés az OfferRequest modellen keresztül az ajanlatkeres táblába
            $offer = new \app\models\OfferRequest();
            $offer->nev = $userName;
            $offer->email = $userEmail;
            $offer->targy = $projectTitle;
            $offer->leiras = $leirasDetails;
            $offer->tipus = '3D Tervezés';
            $offer->status = 'pending';
            $offer->created_at = date('Y-m-d H:i:s');

            if ($offer->save()) {
                return ['success' => true, 'message' => '3D árajánlatkérésed sikeresen rögzítettük!'];
            }

            return ['success' => false, 'message' => 'Hiba történt az adatbázis mentés során.'];
        } catch (\Exception $e) {
            Yii::error("3D Mentési hiba: " . $e->getMessage(), 'ai3d');
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

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
        $order->status = 'pending';

        if ($order->save()) {
            // MENTÉS a főközponti ajanlatkeres táblába is:
            $user = !Yii::$app->user->isGuest ? Yii::$app->user->identity : null;
            $nev = $user ? $user->username : $request->post('user_name', 'Vendég');
            $email = $user ? $user->email : $request->post('user_email', '');

            if (!empty($email)) {
                Yii::$app->db->createCommand()->insert('ajanlatkeres', [
                    'nev' => $nev,
                    'email' => $email,
                    'targy' => $order->title,
                    'leiras' => $order->description ?: '3D Koncepció Megrendelés',
                    'tipus' => '3D Tervezés',
                    'status' => 'pending',
                    'created_at' => date('Y-m-d H:i:s'),
                ])->execute();
            }

            return [
                'success' => true,
                'order_id' => $order->id,
                'message' => 'A megrendelés sikeresen továbbítva az adminisztrátornak!',
            ];
        }

        return ['success' => false, 'errors' => $order->getErrors()];
    }

    public function actionGetTemplates()
    {
        $q = mb_strtolower(Yii::$app->request->get('q', ''), 'UTF-8');

        $query = (new \yii\db\Query())
            ->select(['id', 'category', 'name', 'shape', 'baseCost' => 'base_cost', 'h', 'w', 'l'])
            ->from('templates_3d');

        if ($q !== '') {
            $query->andWhere(['or', ['like', 'LOWER(name)', $q], ['like', 'LOWER(category)', $q]]);
        }

        $dbItems = $query->all();
        $groupedResults = [];

        foreach ($dbItems as $item) {
            $cat = $item['category'];
            $groupedResults[$cat][] = [
                'id' => $item['id'],
                'text' => $item['name'],
                'categoryName' => $item['category'],
                'shape' => $item['shape'],
                'baseCost' => (int)$item['baseCost'],
                'h' => (int)$item['h'],
                'w' => (int)$item['w'],
                'l' => (int)$item['l'],
            ];
        }

        $finalResults = [];
        foreach ($groupedResults as $catName => $children) {
            $finalResults[] = ['text' => $catName, 'children' => $children];
        }

        return ['results' => $finalResults];
    }

    public function actionGetMaterials()
    {
        $q = mb_strtolower(Yii::$app->request->get('q', ''), 'UTF-8');

        $query = (new \yii\db\Query())
            ->select(['id', 'category', 'name', 'code', 'price_factor'])
            ->from('materials_3d');

        if ($q !== '') {
            $query->andWhere(['or', ['like', 'LOWER(name)', $q], ['like', 'LOWER(code)', $q], ['like', 'LOWER(category)', $q]]);
        }

        $dbItems = $query->all();
        $groupedResults = [];

        foreach ($dbItems as $item) {
            $cat = $item['category'];
            $groupedResults[$cat][] = [
                'id' => $item['id'],
                'text' => $item['name'] . ' (' . $item['code'] . ')',
                'code' => $item['code'],
                'category' => $item['category'],
                'factor' => (float)$item['price_factor'],
                'color_hex' => '#95a5a6',
                'roughness' => 0.3,
                'metalness' => 0.8
            ];
        }

        $finalResults = [];
        foreach ($groupedResults as $catName => $children) {
            $finalResults[] = ['text' => $catName, 'children' => $children];
        }

        return ['results' => $finalResults];
    }
}