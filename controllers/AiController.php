<?php

namespace app\controllers;

use Yii;
use app\models\AiTasks;
use app\models\AiSearch; // Fontos: ezt a modellt használd a kereséshez
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\base\Exception;
use app\models\OfferRequest;
use yii\web\Response;

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
                    // Tudásbázis lekérdezés
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
                    . "2. KÉRDEZZ VISSZA ÉS GYŰJTS INFORMÁCIÓT: Amikor az ügyfél felvet egy problémát, tegyél fel 1-2 tisztázó kérdést (pl. 'Milyen szoftvereket használtok jelenleg?', 'Hetente hány órát vesz igénybe ez a folyamat?'), hogy pontosabb képet kapjunk a feladatról az adminisztrátor számára.\n"
                    . "3. ÖSZTÖNÖZZ AJÁNLATKÉRÉSRE: Válaszaid végén mindig finoman említsd meg, hogy a bal oldali 'Ajánlatkérés Indítása' gombra kattintva azonnal elküldheti ezt az igényt, és az AI automatikusan összefoglalja a megbeszélteket a csapatunknak.\n"
                    . "4. NO MARKDOWN: Soha ne használj semmilyen Markdown formázást (ne használj csillagokat *, félkövér jelölést **, listajeleket vagy hashtageket ###)! A válaszaidat kizárólag sima, tiszta, folyószöveges formában írd.\n"
                    . "5. Válaszaid legyenek tömörek, szakmaiak, elegánsak és meggyőzőek.\n\n";

                    $fullPrompt = $systemPrompt . "Felhasználó kérdése: " . $userMessage;

                    $aiResponse = $this->callGeminiApi($fullPrompt);

                    if (strpos($aiResponse, 'HIBA') !== false || strpos($aiResponse, 'DEBUG') !== false) {
                        throw new Exception("AI API hiba: " . $aiResponse);
                    }

                    $model->response = $aiResponse;
                    $model->status = 'completed';
                    
                } catch (\Exception $e) {
                    Yii::error("AI hiba: " . $e->getMessage(), 'ai_process');
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
        // Szigorú admin jogosultság ellenőrzés
        if (Yii::$app->user->isGuest || Yii::$app->user->identity->username !== 'admin') {
            throw new \yii\web\ForbiddenHttpException('Nincs jogosultságod az oldal megtekintéséhez.');
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
     * Dedikált AJAX végpont az AI Automatizációs Chatbothoz
     */
    /**
     * Dedikált AJAX végpont az AI Automatizációs Chatbothoz (Teljes Memóriával és Sales-Fókusszal)
     */
    /**
     * Dedikált AJAX végpont az AI Automatizációs Chatbothoz (Tényalapú Memóriával és Egységes Hangnemmel)
     */
   /**
     * Dedikált AJAX végpont az AI Automatizációs Chatbothoz (Tényalapú Memória & Spammentes Párbeszéd)
     */
    public function actionChatAjax()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $userMessage = Yii::$app->request->post('message');
        $chatHistory = Yii::$app->request->post('chatHistory', '');

        if (empty($userMessage)) {
            return ['success' => false, 'error' => 'Üres üzenet.'];
        }

        // Szigorú Értékesítési, Tényalapú, Természetes & SPAMMENTES System Prompt
        $systemPrompt = "Te a cégünk AI Automatizációs Értékesítési és Igényfelmérő Szakértője vagy. "
            . "Kizárólag üzleti automatizációval, munkafolyamat-optimalizációval és AI integrációval kapcsolatos kérdésekre válaszolhatsz.\n\n"
            . "SZIGORÚ MŰKÖDÉSI SZABÁLYOK:\n"
            . "1. HANGNEM: Használj kivétel nélkül EGYSÉGES, BARÁTSÁGOS ÉS TISZTELETTUDÓ TEGEZÉST! Soha ne használj magázó formákat ('Ön', 'Önnek', 'Önöknek')!\n"
            . "2. ABSZOLÚT MEMÓRIA ÉS TÉNYEK: Szigorúan olvasd végig a beszélgetési előzményeket! Amit a felhasználó egyszer már elmondott (pl. hogy telefonszervize van, hány órát dolgozik Excelben, hány munkatársa van), AZT MEG KELL JEGYEZNED! SO-HA ne mondd, hogy 'még nem említetted' vagy 'nem tudom mivel foglalkozol', ha ez a beszélgetésből már kiderült! Ha a felhasználó visszakérdez ('Mivel foglalkozok?'), azonnal idézd fel a pontos tényeket, amiket korábban megadott neked.\n"
            . "3. SOHA NE HASZNÁLJ REKLÁM-SABLONSZÖVEGET A VÁLASZOK VÉGÉN: SOHA ne fűzd hozzá minden üzenet végéhez az 'Ajánlatkérés Indítása' gombra terelő sablonszöveget! Ez kifejezetten idegesíti az ügyfelet. Válaszolj természetesen, közvetlenül és kérdezz vissza. Kizárólag akkor említsd meg finoman a bal oldali ajánlatkérő gombot, ha a felhasználó kifejezetten árajánlatot vagy árakat kér, vagy ha már minden szükséges részletet tisztáztatok!\n"
            . "4. ZERO DIY / ZERO TECHNIKAI LÉPÉSEK: Ne magyarázd el a megoldás technikai lépéseit, ne adj tanácsot arra, hogyan csinálja meg maga! Kizárólag azt emeld ki, hogy a MI CSAPATUNK ezt a terhet teljesen leveszi a válláról és kulcsrakészen megépíti a számára.\n"
            . "5. KÉRDEZZ VISSZA ÉS GYŰJTS INFORMÁCIÓT: Tegyél fel 1-2 konkrét tisztázó kérdést, de Kizárólag OLYAN ADATRA kérdezz rá, amit a felhasználó MÉG NEM mondott el!\n"
            . "6. NO MARKDOWN: Soha ne használj semmilyen Markdown formázást (nincsenek csillagok *, félkövér **, hashtagek ###, listajelek). Sima, tiszta folyószöveget írj.\n\n";

        $fullPrompt = $systemPrompt;
        
        if (!empty($chatHistory) && trim($chatHistory) !== "--- Chat Előzmény a Modalhoz ---") {
            $fullPrompt .= "EDDIGI BESZÉLGETÉS ELŐZMÉNYEI:\n" . $chatHistory . "\n\n";
        }
        
        $fullPrompt .= "FELHASZNÁLÓ ÚJ ÜZENETE: " . $userMessage;

        // Meghívjuk a Gemini API-t
        $aiResponse = $this->callGeminiApi($fullPrompt);

        if (strpos($aiResponse, 'HIBA') !== false || strpos($aiResponse, 'DEBUG') !== false) {
            return ['success' => false, 'reply' => 'Elnézést, a chatbot jelenleg nem elérhető.'];
        }

        return ['success' => true, 'reply' => $aiResponse];
    }

    private function callGeminiApi($prompt)
    {
        $apiKey = $_ENV['GEMINI_API_KEY'] ?? Yii::$app->params['geminiApiKey'] ?? null;
        
        if (!$apiKey) {
            Yii::error("API kulcs hiányzik!", 'api');
            return "HIBA: Az API kulcs nincs konfigurálva.";
        }
        
        $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent?key=' . $apiKey;

        $data = [
            'contents' => [
                ['parts' => [['text' => $prompt]]]
            ]
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($error) {
            Yii::error("Curl hiba: " . $error, 'api');
            return "HÁLÓZATI HIBA: " . $error;
        }

        $decodedResponse = json_decode($response, true);
        
        if ($httpCode !== 200) {
            Yii::error("API hiba ($httpCode): " . $response, 'api');
            return "DEBUG HIBA (API válasz $httpCode): " . $response;
        }
        
        return $decodedResponse['candidates'][0]['content']['parts'][0]['text'] ?? "Nem érkezett válasz.";
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

        // Senior AI Architect Prompt a tömbösített, strukturált összefoglalóhoz
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
            . "[Konkrét cselekvési terv a kapcsolatfelvételhez]\n\n"
            . "A feldolgozandó beszélgetés előzménye:\n";

        $fullPrompt = $systemPrompt . $chatHistory;
        $rawSummary = $this->callGeminiApi($fullPrompt);

        // Tisztítás a biztonság kedvéért
        $cleanSummary = str_replace(['**', '*', '###', '##', '#'], '', $rawSummary);

        return ['success' => true, 'summary' => trim($cleanSummary)];
    }
}