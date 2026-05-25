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

    /**
     * @inheritDoc
     */

    public $enableCsrfValidation = false;

    public function init()
    {
        parent::init();
        $this->layout = 'admin'; // A views/layouts/admin.php fájlt fogja használni
    }
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'view', 'create', 'update', 'delete'], // Ezeket védjük
                'rules' => [
                    [
                        'actions' => ['index', 'view', 'create', 'update', 'delete'],
                        'allow' => true,
                        // MÓDOSÍTÁS: '@' helyett a parancssorban létrehozott jogosultság nevet használjuk
                        'roles' => ['managePosts'], 
                    ],
                ],
                // OPCIONÁLIS: Ha egy bejelentkezett, de nem admin user téved ide, tiszta hibaüzenetet kap
                'denyCallback' => function ($rule, $action) {
                     Yii::$app->controller->layout = 'error_layout'; 
                    throw new \yii\web\ForbiddenHttpException('Nincs jogosultságod az admin felület eléréséhez.');
                },
            ],
        ];
    }

    /**
     * Egyedi AJAX ajánlatkérés beküldés fogadása (B opció)
     */
    public function actionBekuldes()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        // Csak POST kérést engedünk be
        if (!Yii::$app->request->isPost) {
            return ['success' => false, 'message' => 'Hibás kérésmód!'];
        }

        // Adatok kinyerése a POST kérésből
        $nev = Yii::$app->request->post('nev');
        $email = Yii::$app->request->post('email');
        $targy = Yii::$app->request->post('targy');
        $tipus = Yii::$app->request->post('tipus');

        // Alapvető szerveroldali validáció
        if (empty($nev) || empty($email) || empty($targy) || empty($tipus)) {
            return ['success' => false, 'message' => 'Minden mező kitöltése kötelező!'];
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'Érvénytelen e-mail cím!'];
        }

        // Új modell példányosítása és feltöltése
        $model = new Ajanlatkeres();
        $model->nev = $nev;
        $model->email = $email;
        $model->targy = $targy;
        $model->tipus = $tipus;
        $model->is_read = 0; // Alapértelmezetten olvasatlan
        $model->letrehozva_at = date('Y-m-d H:i:s'); // Aktuális időbélyeg

        // Mentés az adatbázisba
        if ($model->save()) {
            return [
                'success' => true, 
                'message' => 'Köszönjük! Az ajánlatkérést sikeresen rögzítettük, hamarosan keresni fogjuk.'
            ];
        }

        // Ha a modell mentése validációs hiba miatt elbukna
        return ['success' => false, 'message' => 'Adatbázis mentési hiba!', 'errors' => $model->getErrors()];
    }

    /**
     * Lists all Ajanlatkeres models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new AjanlatkeresSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

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

    /**
     * Displays a single Ajanlatkeres model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        if ($model->is_read == 0) {
            $model->is_read = 1;
            $model->save(false); 
        }

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Ajanlatkeres model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Ajanlatkeres();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Ajanlatkeres model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Ajanlatkeres model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Ajanlatkeres model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Ajanlatkeres the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Ajanlatkeres::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}