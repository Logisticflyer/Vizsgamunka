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

                    $systemPrompt = "Te egy szakképzett fejlesztő és e-kereskedelmi szakértő asszisztens vagy. Válaszaid legyenek szakmaiak, tömörek.\n\n";
                    $fullPrompt = $systemPrompt . $context . "\nFelhasználó kérdése: " . $model->prompt;

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

        // Ha beküldték az ajánlatkérő űrlapot
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
     * Dedikált AJAX végpont az AI Automatizációs Chatbothoz
     */
    public function actionChatAjax()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $userMessage = Yii::$app->request->post('message');

        if (empty($userMessage)) {
            return ['success' => false, 'error' => 'Üres üzenet.'];
        }

        // Szigorú System Prompt, hogy a chatbot kizárólag az automatizációra fókuszáljon
        $systemPrompt = "Te egy szigorúan szakosodott AI Automatizációs szakértő asszisztens vagy. "
            . "Kizárólag üzleti automatizációval, munkafolyamat-optimalizációval, digitalizációval és AI integrációval "
            . "kapcsolatos kérdésekre válaszolhatsz. Ha a felhasználó bármilyen más témáról kérdez (pl. időjárás, játékok, magánélet), "
            . "akkor udvariasan, de határozottan tereld vissza a témát az AI automatizációra. Válaszaid legyenek tömörek, szakmaiak és meggyőzőek.\n\n";

        $fullPrompt = $systemPrompt . "Felhasználó kérdése: " . $userMessage;

        // Meghívjuk a már meglévő, bevált Gemini API metódusunkat
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
}