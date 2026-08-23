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
                    [
                        'allow' => true,
                        'actions' => ['index', 'create', 'automation', 'chat-ajax', 'summarize-chat'],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['offers', 'offer-details', 'update-status'],
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return !Yii::$app->user->isGuest && Yii::$app->user->identity->username === 'admin';
                        },
                    ],
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
        
        // Kizárólag a bejelentkezett felhasználó saját feladatai
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

                    $model->response = $this->cleanMarkdown($aiResponse);
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

        if ($request->isPost && $request->post('OfferForm')) {
            $postData = $request->post('OfferForm');
            
            $offer = new OfferRequest();

            // Bejelentkezett felhasználó adatainak előnyben részesítése
            $isLoggedIn = !Yii::$app->user->isGuest;
            $userEmail = $isLoggedIn ? (Yii::$app->user->identity->email ?? null) : null;
            $userName = $isLoggedIn ? (Yii::$app->user->identity->username ?? Yii::$app->user->identity->nev ?? null) : null;

            $offer->name = !empty($userName) ? $userName : trim($postData['name'] ?? '');
            $offer->email = !empty($userEmail) ? $userEmail : filter_var($postData['email'] ?? '', FILTER_VALIDATE_EMAIL);
            $offer->message = trim($postData['message'] ?? '');
            $offer->chat_context = $postData['chat_context'] ?? '';
            
            // Profiloldali azonosításhoz és megjelenítéshez szükséges mezők
            $offer->targy = 'AI Automatizációs Ajánlatkérés';
            $offer->tipus = 'AI';
            $offer->status = 'new';
            $offer->created_at = date('Y-m-d H:i:s');

            if ($offer->email && !empty($offer->name) && $offer->save()) {
                Yii::$app->session->setFlash('success', 'Köszönjük az ajánlatkérésed! Munkatársunk hamarosan felveszi veled a kapcsolatot.');
            } else {
                Yii::$app->session->setFlash('error', 'Kérjük, ellenőrizd az adataid helyességét (név és érvényes email-cím megadása kötelező).');
            }

            return $this->redirect(['ai/automation']);
        }

        return $this->render('automation');
    }

    /**
     * Admin nézet a beérkezett ajánlatkérésekhez (Lead Management)
     */
    public function actionOffers()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => OfferRequest::find(),
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                    'id' => SORT_DESC,
                ],
            ],
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $this->render('offers', [
            'offers' => $dataProvider->getModels(),
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Egy konkrét ajánlatkérés részletes adatlapja az Admin számára
     */
    public function actionOfferDetails($id)
    {
        $offer = OfferRequest::findOne($id);

        if (!$offer) {
            throw new NotFoundHttpException('A keresett ajánlatkérés nem található.');
        }

        return $this->render('offer-details', [
            'offer' => $offer,
        ]);
    }

    /**
     * Státusz frissítése AJAX-on keresztül az Admin felületről
     */
    public function actionUpdateStatus()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $id = Yii::$app->request->post('id');
        $status = Yii::$app->request->post('status');

        if (!$id || !$status) {
            return ['success' => false, 'error' => 'Hiányzó paraméterek.'];
        }

        $offer = OfferRequest::findOne($id);
        if (!$offer) {
            return ['success' => false, 'error' => 'Ajánlatkérés nem található.'];
        }

        try {
            $offer->status = $status;
            if ($offer->save(false, ['status'])) {
                return ['success' => true];
            }
            return ['success' => false, 'error' => 'Sikertelen mentés.'];
        } catch (\Exception $e) {
            Yii::error("Státusz frissítési hiba: " . $e->getMessage(), 'db');
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Bombabiztos AJAX végpont az AI Chatbothoz (Duplikáció-szűrt & Bővített memóriakezelés)
     */
    public function actionChatAjax()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $userMessage = trim((string)Yii::$app->request->post('message', ''));
        $chatHistory = trim((string)Yii::$app->request->post('chatHistory', ''));

        if (empty($userMessage)) {
            return ['success' => false, 'reply' => 'Kérjük, írj be egy üzenetet!'];
        }

        // Előzmény tisztítása és duplikáció szűrése
        if (!empty($chatHistory)) {
            $chatHistory = str_replace($userMessage, '', $chatHistory);
        }

        if (mb_strlen($chatHistory) > 2500) {
            $chatHistory = "..." . mb_substr($chatHistory, -2500);
        }

        $systemPrompt = "Te a cégünk AI Automatizációs Értékesítési és Igényfelmérő Szakértője vagy. "
            . "Kizárólag üzleti automatizációval, munkafolyamat-optimalizációval és AI integrációval kapcsolatos kérdésekre válaszolhatsz.\n\n"
            . "SZIGORÚ MŰKÖDÉSI SZABÁLYOK:\n"
            . "1. HANGNEM: Használj kivétel nélkül EGYSÉGES, BARÁTSÁGOS ÉS TISZTELETTUDÓ TEGEZÉST!\n"
            . "2. ABSZOLÚT MEMÓRIA ÉS TÉNYEK: Olvasd végig a beszélgetési előzményeket!\n"
            . "3. SOHA NE HASZNÁLJ REKLÁM-SABLONSZÖVEGET A VÁLASZOK VÉGÉN!\n"
            . "4. ZERO DIY / ZERO TECHNIKAI LÉPÉSEK!\n"
            . "5. KÉRDEZZ VISSZA ÉS GYŰJTS INFORMÁCIÓT!\n"
            . "6. NO MARKDOWN: Kizárólag sima, tiszta folyószöveget írj.";

        $fullPrompt = "";
        if (!empty($chatHistory) && trim($chatHistory) !== "--- Chat Előzmény a Modalhoz ---") {
            $fullPrompt .= "ELŐZMÉNYEK:\n" . trim($chatHistory) . "\n\n";
        }
        $fullPrompt .= "ÚJ KÉRDÉS: " . $userMessage;

        $aiResponse = $this->callGeminiApi($fullPrompt, $systemPrompt);

        if (strpos($aiResponse, 'HIBA:') === 0) {
            return [
                'success' => false, 
                'reply' => 'Az AI szolgáltatás jelenleg nem érhető el. Ellenőrizd az API kulcs beállításait!'
            ];
        }

        return ['success' => true, 'reply' => $this->cleanMarkdown($aiResponse)];
    }

    /**
     * Elemzi a teljes chat előzményt és egy strukturált projekt-specifikációt generál az adminnak.
     */
    public function actionSummarizeChat()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $chatHistory = trim((string)Yii::$app->request->post('chatHistory', ''));

        if (empty($chatHistory) || $chatHistory === "--- Chat Előzmény a Modalhoz ---") {
            return [
                'success' => true, 
                'summary' => "Az ügyfél még nem kezdeményezett érdemi beszélgetést az AI asszisztenssel. Közvetlen megkeresés."
            ];
        }

        if (mb_strlen($chatHistory) > 4000) {
            $chatHistory = mb_substr($chatHistory, -4000);
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

        if (strpos($rawSummary, 'HIBA:') === 0) {
            Yii::error("Gemini hiba az actionSummarizeChat-ben: " . $rawSummary, 'ai_summary');
            return [
                'success' => false, 
                'summary' => 'Nem sikerült az összefoglaló generálása az AI API hibája miatt.'
            ];
        }

        return ['success' => true, 'summary' => $this->cleanMarkdown($rawSummary)];
    }

    /**
     * OPTIMALIZÁLT SEGÉDFÜGGVÉNY DÁTUM-INJEKTÁLÁSSAL ÉS AUTOMATIKUS FALLBACK MODEL-LÁNCCAL
     */
    /**
     * Működő és jövőálló Gemini API hívás duplázott API verzió (v1 & v1beta) és Modell fallback lánccal
     */
    private function callGeminiApi($prompt, $systemInstruction = null)
    {
        $rawApiKey = Yii::$app->params['geminiApiKey'] 
            ?? Yii::$app->params['cadGeminiApiKey'] 
            ?? Yii::$app->params['vintedGeminiApiKey']
            ?? $_ENV['GEMINI_API_KEY'] 
            ?? $_SERVER['GEMINI_API_KEY'] 
            ?? null;

        if (!$rawApiKey || strpos($rawApiKey, 'dummy') !== false) {
            Yii::error("Hiányzó vagy teszt API kulcs: " . $rawApiKey, 'api');
            return "HIBA: Az API kulcs nincs megfelelően beállítva a config/params.php fájlban.";
        }

        $cleanApiKey = trim(preg_replace('/^Bearer\s+/i', '', $rawApiKey), " \t\n\r\0\x0B\"'");

        date_default_timezone_set('Europe/Budapest');
        $now = new \DateTime();
        $dateTimePrefix = "JELENLEGI SYSTEM DÁTUM ÉS IDŐ: " . $now->format('Y-m-d H:i:s') . ".\n\n";
        $finalSystemInstruction = $dateTimePrefix . ($systemInstruction ?? '');

        // Hivatalos Google AI Studio modellek
        $modelsToTry = [
            'gemini-3.6-flash',
            'gemini-3.5-flash',
            'gemini-3.1-pro-preview',
            'gemini-3.1-flash-lite',
        ];

        $attemptLogs = [];

        foreach ($modelsToTry as $modelName) {
            $url = "https://generativelanguage.googleapis.com/v1beta/models/{$modelName}:generateContent?key=" . $cleanApiKey;

            $data = [
                'system_instruction' => [
                    'parts' => [
                        ['text' => mb_convert_encoding($finalSystemInstruction, 'UTF-8', 'UTF-8')]
                    ]
                ],
                'contents' => [
                    [
                        'parts' => [
                            ['text' => mb_convert_encoding($prompt, 'UTF-8', 'UTF-8')]
                        ]
                    ]
                ]
            ];

            $jsonPayload = json_encode($data, JSON_UNESCAPED_UNICODE);

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonPayload);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json; charset=utf-8']);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_TIMEOUT, 20);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);

            if (!$error && $httpCode === 200) {
                $decodedResponse = json_decode($response, true);
                $textReply = $decodedResponse['candidates'][0]['content']['parts'][0]['text'] ?? null;
                if (!empty($textReply)) {
                    return $textReply;
                }
            }

            $attemptLogs[] = "{$modelName} (HTTP {$httpCode}): " . ($error ?: $response);
        }

        Yii::error("Gemini API Összes modell elutasítva: " . implode(" | ", $attemptLogs), 'api');
        return "HIBA: Egyik Gemini modell sem válaszolt megfelelően.";
    }

    /**
     * TISZTÍTÓ SEGÉDFÜGGVÉNY A MARKDOWN KARAKTEREK ELTÁVOLÍTÁSÁRA
     */
    private function cleanMarkdown($text)
    {
        if (empty($text)) {
            return '';
        }
        return trim(str_replace(['**', '*', '###', '##', '#', '`'], '', $text));
    }
}