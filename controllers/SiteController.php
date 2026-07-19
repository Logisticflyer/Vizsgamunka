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
                // CSAK a belső oldalakat védjük. Az 'index' kikerült, mert ott lépnek be!
                'only' => ['choose', 'logout'], 
                'rules' => [
                    [
                        'actions' => ['choose', 'logout'],
                        'allow' => true,
                        'roles' => ['@'], // Csak belépve
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
        // 1. Ha már be van jelentkezve, ne mutassuk az indexet
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['site/choose']);
        }

        $this->layout = 'main';

        $model = new LoginForm();
        $signupModel = new \app\models\SignupForm();

        // 2. Regisztráció kezelése
        if (Yii::$app->request->isPost && isset($_POST['signup-button'])) {
            if ($signupModel->load(Yii::$app->request->post()) && $signupModel->signup()) {
                Yii::$app->session->setFlash('success', 'Sikeres regisztráció! Most már bejelentkezhetsz.');
                return $this->redirect(['site/index']);
            } else {
                // ITT A LÉNYEG: Ha hiba van (pl. foglalt email), ne csak csendben maradjunk,
                // hanem adjunk egy figyelmeztetést!
                Yii::$app->session->setFlash('error', 'A regisztráció nem sikerült. Kérlek, ellenőrizd az adataidat.');
                
                // Ez a sor garantálja, hogy a felhasználó látja a hibaüzenetet az űrlapban
                return $this->render('index', [
                    'model' => new \app\models\LoginForm(), // Üres login modell
                    'signupModel' => $signupModel,          // Hibás regisztrációs modell
                ]);
            }
        }

        // 3. Bejelentkezés kezelése
        if (Yii::$app->request->isPost && isset($_POST['LoginForm'])) {
            if ($model->load(Yii::$app->request->post()) && $model->login()) {
                return $this->redirect(['site/choose']);
            } else {
                Yii::$app->session->setFlash('error', 'Hibás felhasználónév vagy jelszó.');
            }
        }

        // 4. Renderelés (ha hiba volt, az $signupModel / $model tartalmazza a hibaüzeneteket a mezők alatt)
        return $this->render('index', [
            'model' => $model,
            'signupModel' => $signupModel,
        ]);
    }

    public function actionProfile()
    {

        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $user = Yii::$app->user->identity;
        $model = new \app\models\UpdateProfileForm($user);

        if ($model->load(Yii::$app->request->post()) && $model->updateProfile()) {
            Yii::$app->session->setFlash('success', 'A profiladataid sikeresen frissítve lettek!');
            return $this->refresh();
        }

        return $this->render('profile', [
            'model' => $model,
            'user' => $user,
        ]);
    }

    public function actionRolunk()
    {
        $this->layout = 'main-no-sidebar';
        return $this->render('rolunk');
    }
    
    public function actionChoose()
    {
        $this->layout = 'main-no-sidebar';       
        return $this->render('choose');
    }

    /**
     * Logout action.
     *
     * @return Response
     */
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

    /**
     * Jelszó visszaállítás kérése (Email bekérése)
     */
    public function actionRequestPasswordReset()
    {
        $this->layout = 'main-no-sidebar'; // Hogy szép letisztult legyen ez az oldal is
        
        $model = new \app\models\PasswordResetRequestForm();
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Nézd meg az e-mail fiókodat! Elküldtük a további lépéseket.');
                return $this->redirect(['index']); // Visszadobjuk a főoldalra
            } else {
                Yii::$app->session->setFlash('success', 'Ha a megadott e-mail cím szerepel a rendszerünkben, elküldtük a további lépéseket.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }


    /**
     * Új jelszó megadása (Token alapján)
     */
    public function actionResetPassword($token)
    {
        $this->layout = 'main-no-sidebar';

        try {
            // Megpróbáljuk betölteni a modellt a tokennel
            $model = new \app\models\ResetPasswordForm($token);
        } catch (\yii\base\InvalidArgumentException $e) {
            // Ha rossz a token (pl. már felhasználták), visszadobjuk a főoldalra hibával
            Yii::$app->session->setFlash('error', $e->getMessage());
            return $this->redirect(['site/index']);
        }

        // Ha elküldte az új jelszót és érvényes
        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'A jelszavadat sikeresen frissítettük! Most már bejelentkezhetsz az új jelszóval.');
            return $this->redirect(['site/index']);
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
}