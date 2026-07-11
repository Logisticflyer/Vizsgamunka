<?php

namespace app\controllers;

use Yii;
use app\models\Ajanlatkeres;
use app\models\AjanlatkeresSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

class AjanlatkeresController extends Controller
{
    public function init()
    {
        parent::init();

        $action = Yii::$app->requestedAction ? Yii::$app->requestedAction->id : null;

        if (in_array($action, ['index', 'view', 'create', 'update', 'delete'])) {
            $this->layout = 'admin';
        }
    }

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'bekuldes' => ['POST'],
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index', 'view', 'create', 'update', 'delete'],
                'rules' => [
                    [
                        'actions' => ['index', 'view', 'create', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['managePosts'],
                    ],
                ],
                'denyCallback' => function () {
                    Yii::$app->controller->layout = 'error_layout';
                    throw new \yii\web\ForbiddenHttpException('Nincs jogosultságod az admin felület eléréséhez.');
                },
            ],
        ];
    }

    public function actionBekuldes()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $model = new Ajanlatkeres();

        if (!$model->load(Yii::$app->request->post(), '')) {
            return [
                'success' => false,
                'message' => 'Nem érkezett feldolgozható adat.',
            ];
        }

        if ($model->validate() && $model->save()) {
            return [
                'success' => true,
                'message' => 'Köszönjük! Az ajánlatkérést sikeresen rögzítettük, hamarosan keresni fogjuk.',
            ];
        }

        return [
            'success' => false,
            'message' => 'Az űrlap ellenőrzése sikertelen.',
            'errors' => $model->getErrors(),
        ];
    }

    public function actionIndex()
    {
        $searchModel = new AjanlatkeresSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $totalCount = Ajanlatkeres::find()->count();
        $aiCount = Ajanlatkeres::find()->where(['tipus' => 'ai'])->count();
        $count3d = Ajanlatkeres::find()->where(['tipus' => '3d'])->count();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'totalCount' => $totalCount,
            'aiCount' => $aiCount,
            'count3d' => $count3d,
        ]);
    }

    public function actionView($id)
    {
        $model = $this->findModel($id);

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    public function actionCreate()
    {
        $model = new Ajanlatkeres();

        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Ajanlatkeres::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('A keresett elem nem található.');
    }
}