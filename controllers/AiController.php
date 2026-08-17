<?php

namespace app\controllers;

use Yii;
use app\models\AiTasks;
use app\models\AiSearch;
use app\models\OfferRequest;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\Response;
use yii\base\Exception;

class AiController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    ['allow' => true, 'roles' => ['@']],
                ],
            ],
        ];
    }

    /**
     * AI Feladatok áttekintő oldala
     */
    public function actionIndex()
    {
        $searchModel = new AiSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        // Biztosítjuk, hogy a felhasználó csak a saját feladatait lássa
        $dataProvider->query->andWhere(['user_id' => Yii::$app->user->id]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Új AI feladat létrehozása és feldolgozása a Tudásbázis alapján
     */
    public function actionCreate()
    {
        $model = new AiTasks();

        if ($model->load(Yii::$app->request->post())) {
            $model->user_id = Yii::$app->user->id;
            $model->status = 'pending';
            $model->created_at = time();
            $model->updated_at = time();

            if ($model->save()) {
                try {
                    // Tudásbázis lekérdezése
                    $knowledgeItems = \app\models\AiKnowledge::find()
                        ->where(['like', 'title', $model->prompt])
                        ->orWhere(['like', 'content', $model->prompt])
                        ->orWhere(['like', 'category', $model->prompt])
                        ->all();

                    $context = "A következő információkat használd forrásként:\n";
                    foreach ($knowledgeItems as $item) {
                        $context .= "- " . $item->content . "\n";
                    }

                    $systemPrompt = "Te a cégünk AI Automatizációs Értékesítési és Szakmai Tanácsadója vagy. "
                        . "Kizárólag üzleti automatizációval, munkafolyamat-optimalizációval, digitalizációval és AI integrációval kapcsolatos kérdésekre válaszolhatsz.\n\n"
                        . "FŐ CÉLOD: Az ügyfél igényeinek és problémáinak feltérképezése, a megvalósíthatóság igazolása és az ügyfél terelése az ajánlatkérés felé.\n\n"
                        . "SZIGORÚ MŰKÖDÉSI SZABÁLYOK:\n"
                        . "1. NE ADJ 'Csináld magad' (DIY) oktatóanyagot vagy lépésről lépésre követhető technikai leírást! Ne azt magyarázd el, hogyan építse meg ő magának, hanem azt, hogy a cégünk hogyan tudja ezt kulcsrakészen, automatizáltan megvalósítani a számára.\n"
                        . "2. KÉRDEZZ VISSZA ÉS GYŰJTS INFORMÁCIÓT: Amikor az ügyfél felvet egy problémát, tegyél fel 1-2 tisztázó kérdést, hogy pontosabb képet kapjunk a feladatról az adminisztrátor számára.\n"
                        . "3. ÖSZTÖNÖZZ AJÁNLATKÉRÉSRE: Válaszaid végén mindig finoman említsd meg az ajánlatkérési lehetőséget.\n"
                        . "4. NO MARKDOWN: Soha ne használj semmilyen Markdown formázást (nincsenek csillagok *, félkövér **, listajelek, hashtagek ###)! A válaszaidat kizárólag sima, tiszta, folyószöveges formában írd.\n"
                        . "5. Válaszaid legyenek tömörek, szakmaiak, elegánsak és meggyőzőek.";

                    $userPrompt = $context . "\nFelhasználó kérdése: " . $model->prompt;

                    $aiResponse = $this->callGeminiApi($userPrompt, $systemPrompt);

                    if (strpos($aiResponse, 'HIBA:') === 0) {
                        throw new Exception("AI API hiba: " . $aiResponse);
                    }

                    $model->response = $aiResponse;
                    $model->status = 'completed';
                    
                } catch (\Exception $e) {
                    Yii::error("AI hiba az actionCreate-ben: " . $e->getMessage(), 'ai_process');
                    $model->response = "Sajnos az AI feldolgozás közben hiba történt.";
                    $model->status = 'failed';
                }

                $model->updated_at = time();
                $model->update(false, ['response', 'status', 'updated_at']);
                
                Yii::$app->session->setFlash('success', 'Feladat feldolgozva!');
                return $this->redirect(['index']);
            }
        }

        return $this->render('create', ['model' => $model]);
    }

    /**
     * AI Automatizációs bemutató oldal és okos ajánlatkérő kezelése
     */
    public function actionAutomation()
    {
        $request = Yii::$app->request;

        // Ha beküldték az ajánlatkérő űrlapot (Modalon keresztül)
        if ($request->isPost && $request->post('OfferForm')) {
            $postData = $request->post('OfferForm');
            
            // Mentés az offer_requests adatbázistáblába
            $db = Yii::$app->db;
            $db->createCommand()->insert('offer_requests', [
                'name' => $postData['name'],
                'email' => $postData['email'],
                'message' => $postData['message'],
                'chat_context' => $postData['chat_context'] ?? '',
                'status' => 'new',
                'created_at' => date('Y-m-d H:i:s'),
            ])->execute();

            Yii::$app->session->setFlash('success', 'Köszönjük az ajánlatkérésed! Munkatársunk hamarosan felveszi veled a kapcsolatot.');
            return $this->redirect(['ai/automation']);
        }

        return $this->render('automation');
    }

    /**
     * Admin nézet a beérkezett ajánlatkérésekhez (Lead Management)
     */
    public function actionOffers()
    {
        if (Yii::$app->user->isGuest || Yii::$app->user->identity->username !== 'admin') {
            throw new ForbiddenHttpException('Nincs jogosultságod az oldal megtekintéséhez.');
        }

        $dataProvider = new ActiveDataProvider([
            'query' => OfferRequest::find()->orderBy(['created_at' => SORT_DESC]),
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('offers', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Státusz frissítése AJAX-on keresztül az Admin felületről
     */
    public function actionUpdateStatus()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (Yii::$app->user->isGuest || Yii::$app->user->identity->username !== 'admin') {
            return ['success' => false, 'error' => 'Nincs jogosultságod a művelethez.'];
        }

        $id = Yii::$app->request->post('id');
        $status = Yii::$app->request->post('status');

        if (!$id || !$status) {
            return ['success' => false, 'error' => 'Hiányzó paraméterek.'];
        }

        try {
            Yii::$app->db->createCommand()->update(
                'offer_requests', 
                ['status' => $status], 
                'id = :id', 
                [':id' => $id]
            )->execute();

            return ['success' => true];
        } catch (\Exception $e) {
            Yii::error("Státusz frissítési hiba: " . $e->getMessage(), 'db');
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Dedikált AJAX végpont az AI Automatizációs Chatbothoz
     */
    /**
     * Dedikált AJAX végpont az AI Automatizációs Chatbothoz
     */
    public function actionChatAjax()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $userMessage = Yii::$app->request->post('message');
        $chatHistory = Yii::$app->request->post('chatHistory', '');

        if (empty($userMessage)) {
            return ['success' => false, 'reply' => 'Üres üzenet.'];
        }

        // System Prompt
        $systemPrompt = "Te a cégünk AI Automatizációs Értékesítési és Igényfelmérő Szakértője vagy. "
            . "Kizárólag üzleti automatizációval, munkafolyamat-optimalizációval és AI integrációval kapcsolatos kérdésekre válaszolhatsz.\n\n"
            . "SZIGORÚ MŰKÖDÉSI SZABÁLYOK:\n"
            . "1. HANGNEM: Használj kivétel nélkül EGYSÉGES, BARÁTSÁGOS ÉS TISZTELETTUDÓ TEGEZÉST!\n"
            . "2. ABSZOLÚT MEMÓRIA ÉS TÉNYEK: Szigorúan olvasd végig a beszélgetési előzményeket!\n"
            . "3. SOHA NE HASZNÁLJ REKLÁM-SABLONSZÖVEGET A VÁLASZOK VÉGÉN!\n"
            . "4. ZERO DIY / ZERO TECHNIKAI LÉPÉSEK!\n"
            . "5. KÉRDEZZ VISSZA ÉS GYŰJTS INFORMÁCIÓT!\n"
            . "6. NO MARKDOWN: Sima, tiszta folyószöveget írj.";

        $fullPrompt = "";
        if (!empty($chatHistory) && trim($chatHistory) !== "--- Chat Előzmény a Modalhoz ---") {
            $fullPrompt .= "EDDIGI TELJES BESZÉLGETÉS ELŐZMÉNYEI:\n" . $chatHistory . "\n\n";
        }
        
        $fullPrompt .= "FELHASZNÁLÓ ÚJ ÜZENETE: " . $userMessage;

        // API hívás
        $aiResponse = $this->callGeminiApi($fullPrompt, $systemPrompt);

        if (strpos($aiResponse, 'HIBA:') === 0) {
            Yii::warning("Gemini hiba/korlát az actionChatAjax-ban: " . $aiResponse, 'ai_chat');
            
            // Ha ráta-korlát (429) történt, success => false-t adunk vissza, hogy a JS NE mentse el az előzmények közé!
            if (strpos($aiResponse, '429') !== false || strpos($aiResponse, 'exceeded') !== false || strpos($aiResponse, 'Quota') !== false) {
                return [
                    'success' => false, 
                    'reply' => 'Az AI asszisztens jelenleg túl sok kérést kapott. Kérlek, várj 10 másodpercet, és próbáld újra!'
                ];
            }
            
            return ['success' => false, 'reply' => $aiResponse];
        }

        return ['success' => true, 'reply' => $aiResponse];
    }

    /**
     * Elemzi a teljes chat előzményt és egy strukturált, profi projekt-specifikációt generál az adminnak.
     */
    public function actionSummarizeChat()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $chatHistory = Yii::$app->request->post('chatHistory');

        if (empty($chatHistory) || trim($chatHistory) === "--- Chat Előzmény a Modalhoz ---") {
            return [
                'success' => true, 
                'summary' => "Az ügyfél még nem kezdeményezett érdemi beszélgetést az AI asszisztenssel. Közvetlen megkeresés."
            ];
        }

        $systemPrompt = "Te egy senior AI rendszermérnök és projektmenedzser vagy. "
            . "A feladatod, hogy az alábbi ügyfélbeszélgetés alapján készíts egy rendkívül profi, strukturált, csoportosított projekt-specifikációt az adminisztrátor számára.\n"
            . "FONTOS: Soha ne használj Markdown karaktereket (*, **, ###)! Kizárólag tiszta, átlátható soremelésekkel és nagybetűs szekciócímekkel tagold a szöveget.\n\n"
            . "A kimeneted szigorúan kövesse ezt a struktúrát:\n"
            . "ÜGYFÉL FŐ CÉLJA:\n"
            . "[Tömör, 1-2 mondatos összefoglaló a problémáról]\n\n"
            . "IGÉNYELT AUTOMATIZÁCIÓK & MODULOK:\n"
            . "- [1. azonosított funkció / munkafolyamat]\n"
            . "- [2. azonosított funkció / munkafolyamat]\n\n"
            . "JAVASOLT TECHNOLÓGIAI STACK:\n"
            . "[Pl. Make.com / n8n / OpenAI API / Custom Webhook / Python]\n\n"
            . "ADMINISZTRÁTOR TEENDŐJE:\n"
            . "[Konkrét cselekvési terv a kapcsolatfelvételhez]";

        $fullPrompt = "A feldolgozandó beszélgetés előzménye:\n" . $chatHistory;
        
        $rawSummary = $this->callGeminiApi($fullPrompt, $systemPrompt);

        // Tisztítás a biztonság kedvéért
        $cleanSummary = str_replace(['**', '*', '###', '##', '#'], '', $rawSummary);

        return ['success' => true, 'summary' => trim($cleanSummary)];
    }

    /**
     * EGYSÉGES, GOLYÓÁLLÓ SEGÉDFÜGGVÉNY AUTOMATIKUS ÚJRAPRÓBÁLKOZÁSSAL ÉS FALLBACKEL
     */
    private function callGeminiApi($prompt, $systemInstruction = null)
    {
        $rawApiKey = Yii::$app->params['geminiApiKey'] 
            ?? Yii::$app->params['cadGeminiApiKey'] 
            ?? Yii::$app->params['vintedGeminiApiKey']
            ?? $_ENV['GEMINI_API_KEY'] 
            ?? $_SERVER['GEMINI_API_KEY'] 
            ?? null;

        if (!$rawApiKey) {
            Yii::error("API kulcs hiányzik!", 'api');
            return "HIBA: Az API kulcs nincs konfigurálva a config/params.php-ban.";
        }

        $cleanApiKey = trim($rawApiKey);
        $cleanApiKey = preg_replace('/^Bearer\s+/i', '', $cleanApiKey);
        $cleanApiKey = trim($cleanApiKey, " \t\n\r\0\x0B\"'");

        $modelsToTry = [
           'gemini-2.5-flash',
           'gemini-2.0-flash-lite',
           'gemini-flash-latest',
           'gemini-2.5-flash-lite'
        ];

        $lastResponse = null;
        $lastHttpCode = 0;
        $hadRateLimit = false;

        foreach ($modelsToTry as $modelName) {
            $url = "https://generativelanguage.googleapis.com/v1beta/models/{$modelName}:generateContent?key=" . $cleanApiKey;

            $data = [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => mb_convert_encoding($prompt, 'UTF-8', 'UTF-8')]
                        ]
                    ]
                ]
            ];

            if ($systemInstruction) {
                $data['systemInstruction'] = [
                    'parts' => [
                        ['text' => mb_convert_encoding($systemInstruction, 'UTF-8', 'UTF-8')]
                    ]
                ];
            }

            $jsonPayload = json_encode($data, JSON_UNESCAPED_UNICODE);

            if ($jsonPayload === false) {
                return "HIBA: A kérés JSON kódolása nem sikerült.";
            }

            // Első próbálkozás az adott modellre
            $ch = curl_init($url);
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

            // Ha 429-es korlátot kapunk, várunk 2.5 másodpercet és ÚJRAPRÓBÁLKOZUNK 1x
            if ($httpCode === 429 || strpos($response, 'exceeded') !== false) {
                $hadRateLimit = true;
                usleep(2500000); // 2.5 másodperc alvás

                $chRetry = curl_init($url);
                curl_setopt($chRetry, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($chRetry, CURLOPT_POST, true);
                curl_setopt($chRetry, CURLOPT_POSTFIELDS, $jsonPayload);
                curl_setopt($chRetry, CURLOPT_HTTPHEADER, ['Content-Type: application/json; charset=utf-8']);
                curl_setopt($chRetry, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($chRetry, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt($chRetry, CURLOPT_TIMEOUT, 35);

                $response = curl_exec($chRetry);
                $httpCode = curl_getinfo($chRetry, CURLINFO_HTTP_CODE);
                $error = curl_error($chRetry);
                curl_close($chRetry);
            }

            // Ha sikerült a válasz
            if (!$error && $httpCode === 200) {
                $decodedResponse = json_decode($response, true);
                $textReply = $decodedResponse['candidates'][0]['content']['parts'][0]['text'] ?? null;
                if ($textReply) {
                    return $textReply;
                }
            }

            $lastHttpCode = $httpCode;
            $lastResponse = $response;

            if (!in_array($httpCode, [404, 429, 500, 503])) {
                break;
            }
        }

        if ($hadRateLimit) {
            return "HIBA: Google API (429) - Quota exceeded. Please wait a few seconds.";
        }

        $decodedErr = json_decode($lastResponse, true);
        $errMsg = $decodedErr['error']['message'] ?? $lastResponse;
        Yii::error("Gemini API hiba ($lastHttpCode): " . $lastResponse, 'api');
        return "HIBA: Google API ($lastHttpCode) - " . $errMsg;
    }
}