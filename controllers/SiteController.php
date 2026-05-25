<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    public $layout = 'main';
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::class,
                'only' => ['logout', 'signup', 'login', 'contact', 'choose', 'rolunk'],
                'rules' => [
                    [
                        // A vendégek (anonim felhasználók) elérhetik a regisztrációt is
                        'actions' => ['login', 'signup', 'contact', 'rolunk'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        // A bejelentkezett userek elérhetik a belső oldalakat
                        'actions' => ['logout', 'contact', 'choose', 'rolunk'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => \yii\filters\VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
    return [
        'error' => [
            'class' => 'yii\web\ErrorAction',
            'layout' => 'error_layout', 
        ],
        'captcha' => [
            'class' => 'yii\captcha\CaptchaAction',
            'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
        ],
    ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    
    public function actionIndex()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['site/choose']);
        }

        $this->layout = 'main';

        $model = new LoginForm();
        $signupModel = new \app\models\SignupForm(); // Létrehozzuk a regisztrációs modellt is

        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirect(['site/choose']);
        }

        return $this->render('index', [
            'model' => $model,
            'signupModel' => $signupModel, // Átadjuk a nézetnek!
        ]);
    }

    public function actionRolunk()
    {
        $this->layout = 'main-no-sidebar';

        return $this->render('rolunk');
    }

    public function actionLogin()
    {
            
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['site/choose']);
        }

        return $this->redirect(['site/index', '#' => 'auth-zone']);
    }
    
    /**
     * Logout action.
     *
     * @return Response
     */


    public function actionSignup()
        {
            if (!Yii::$app->user->isGuest) {
                return $this->redirect(['site/choose']);
            }

            $this->layout = 'main'; 
            
            $signupModel = new \app\models\SignupForm();

            if ($signupModel->load(Yii::$app->request->post())) {
                $user = $signupModel->signup();
                Yii::error(['user_returned' => $user ? $user->id : 'NULL', 'errors' => $signupModel->getErrors()], 'signup_debug');

                if ($user) {
                    $loginResult = Yii::$app->user->login($user);
                    Yii::error(['login_result' => $loginResult], 'signup_debug');

                    if ($loginResult) {
                        Yii::$app->session->setFlash('success', 'Sikeres regisztráció és belépés!');
                        return $this->redirect(['site/choose']);
                    }
                }
            }

            $model = new LoginForm();
            
            return $this->render('index', [
                'model' => $model,
                'signupModel' => $signupModel,
            ]);
        }
        public function actionChoose()
        {
        $this->layout = 'main';
        $this->layout = 'main-no-sidebar';       
            return $this->render('choose');
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact($type = null)
    {
        $this->layout = 'main';
    $model = new \app\models\ContactForm();

    // KRITIKUS JAVÍTÁS: Ha érkezett típus az URL-ben, átadjuk a form modellnek a renderelés ELŐTT
    if ($type !== null && in_array($type, ['ai', '3d'])) {
        // Feltételezve, hogy a ContactForm-ban van egy 'tipus' nevű publikus változó/attribútum
        $model->tipus = $type; 
    }

    if ($model->load(Yii::$app->request->post())) {
        if ($model->validate()) {
            $dbModel = new \app\models\Ajanlatkeres();

            $dbModel->nev = $model->name;
            $dbModel->email = $model->email;
            $dbModel->targy = $model->subject;
            $dbModel->leiras = $model->body;
            
            // Itt a biztonság kedvéért a már validált form vagy az URL paraméter értékét mentjük
            $dbModel->tipus = $model->tipus ?? $type; 

            if ($dbModel->save(false)) {
                Yii::$app->session->setFlash('contactFormSubmitted');
                return $this->refresh();
            }
        } else {
            // Ha a ContactForm validációja elbukik, kiíratjuk a hibákat
            Yii::error($model->getErrors(), 'contact_errors');
        }
    }

    // Itt adjuk át a már előre kitöltött $model-t a view-nak
    return $this->render('contact', [
        'model' => $model,
        'type'  => $type,
    ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
