<?php

namespace app\controllers;

use Yii;
use app\models\AiTasks;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;

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
        $query = AiTasks::find()->where(['user_id' => Yii::$app->user->id]);
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['created_at' => SORT_DESC]],
        ]);

        return $this->render('index', ['dataProvider' => $dataProvider]);
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
                // Biztonságos hívás
                $aiResponse = $this->callGeminiApi($model->prompt);
                
                $model->response = $aiResponse;
                $model->status = 'completed';
                $model->updated_at = time();
                $model->update(false, ['response', 'status', 'updated_at']);

                Yii::$app->session->setFlash('success', 'AI feladat sikeresen feldolgozva!');
                return $this->redirect(['index']);
            }
        }

        return $this->render('create', ['model' => $model]);
    }

    private function callGeminiApi($prompt)
    {
        // Kulcs lekérése biztonságosan a configból
       $apiKey = $_ENV['GEMINI_API_KEY'] ?? null;
        
        if (!$apiKey || $apiKey === 'IDE_MÁSOLD_BE_AZ_ÚJ_KULCSODAT') {
            return "HIBA: Az API kulcs nincs konfigurálva a params.php fájlban.";
        }
        
        $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=' . $apiKey;

        $data = [
            'contents' => [
                ['parts' => [['text' => "Te egy profi magyar nyelvű AI asszisztens vagy. Fogalmazz röviden. A felhasználó kérése: " . $prompt]]]
            ]
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        
        // XAMPP SSL fix
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($error) {
            return "HÁLÓZATI HIBA: " . $error;
        }
        
        $decodedResponse = json_decode($response, true);
        
        if (isset($decodedResponse['candidates'][0]['content']['parts'][0]['text'])) {
            return $decodedResponse['candidates'][0]['content']['parts'][0]['text'];
        }
        
        return "DEBUG HIBA (Google válasz): " . (is_array($decodedResponse) ? json_encode($decodedResponse) : $response);
    }

    public function actionView($id)
    {
        $model = AiTasks::findOne(['id' => $id, 'user_id' => Yii::$app->user->id]);
        if ($model === null) {
            throw new NotFoundHttpException('A keresett oldal nem található.');
        }
        return $this->render('view', ['model' => $model]);
    }

    public function actionUpdate($id)
    {
        $model = AiTasks::findOne(['id' => $id, 'user_id' => Yii::$app->user->id]);
        if ($model === null) {
            throw new NotFoundHttpException('A keresett elem nem található.');
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Módosítva!');
            return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->render('update', ['model' => $model]);
    }

    public function actionDelete($id)
    {
        $model = AiTasks::findOne(['id' => $id, 'user_id' => Yii::$app->user->id]);
        if ($model !== null) {
            $model->delete();
            Yii::$app->session->setFlash('success', 'Törölve.');
        }
        return $this->redirect(['index']);
    }

    public function actionListModels()
{
    $apiKey = Yii::$app->params['geminiApiKey'];
    $url = 'https://generativelanguage.googleapis.com/v1beta/models?key=' . $apiKey;

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    header('Content-Type: application/json');
    echo $response; // Ez kiírja az összes elérhető modellt
    exit;
}
}