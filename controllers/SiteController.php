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
     * CSRF védelem rugalmas kezelése az űrlapok hibamentes beküldéséhez
     */
    public function beforeAction($action)
    {
        // Ha POST kérés érkezik az indexre vagy signupra, átengedjük CSRF hiba nélkül
        if (in_array($action->id, ['index', 'signup'])) {
            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['choose', 'logout', 'profile'], 
                'rules' => [
                    [
                        'actions' => ['choose', 'logout', 'profile'],
                        'allow' => true,
                        'roles' => ['@'], // Csak bejelentkezett felhasználóknak
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
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
                'layout' => 'main', 
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Főoldal kezelése
     */
    public function actionIndex()
    {
        // 1. HA BE VAGY JELENTKEZVE
        if (!Yii::$app->user->isGuest) {
            $this->layout = 'main';

            // HA ADMIN: Összegyűjtjük a KPI adatokat
            if (Yii::$app->user->identity->username === 'admin') {
                $totalOffers = 0;
                $recentOffers = [];
                
                if (class_exists('\app\models\OfferRequest')) {
                    $totalOffers = \app\models\OfferRequest::find()->count();
                    $recentOffers = \app\models\OfferRequest::find()->orderBy(['id' => SORT_DESC])->limit(5)->all();
                } elseif (class_exists('\app\models\Ajanlatkeres')) {
                    $totalOffers = \app\models\Ajanlatkeres::find()->count();
                    $recentOffers = \app\models\Ajanlatkeres::find()->orderBy(['id' => SORT_DESC])->limit(5)->all();
                }

                $totalKnowledge = 0;
                if (class_exists('\app\models\AiKnowledge')) {
                    $totalKnowledge = \app\models\AiKnowledge::find()->count();
                }

                return $this->render('index', [
                    'totalOffers' => $totalOffers,
                    'recentOffers' => $recentOffers,
                    'totalKnowledge' => $totalKnowledge,
                ]);
            }

            // SIMA FELHASZNÁLÓ ÁG -> Közvetlenül a Choose (választó) nézetre irányítjuk
            return $this->redirect(['site/choose']);
        }

        // 2. HA VENDÉG VAGY
        $this->layout = 'main';

        $model = new LoginForm();
        $signupModel = new \app\models\SignupForm();

        // Regisztráció feldolgozása
        if (Yii::$app->request->isPost && (isset($_POST['signup-button']) || isset($_POST['SignupForm']))) {
            if ($signupModel->load(Yii::$app->request->post()) && $signupModel->signup()) {
                Yii::$app->session->setFlash('success', 'Sikeres regisztráció! Most már bejelentkezhetsz.');
                return $this->redirect(['site/index']);
            } else {
                Yii::$app->session->setFlash('error', 'A regisztráció nem sikerült. Kérlek, ellenőrizd az adataidat.');
            }
        }

        // Bejelentkezés feldolgozása
        if (Yii::$app->request->isPost && isset($_POST['LoginForm'])) {
            if ($model->load(Yii::$app->request->post()) && $model->login()) {
                if (Yii::$app->user->identity->username === 'admin') {
                    return $this->redirect(['site/index']);
                }
                return $this->redirect(['site/choose']);
            } else {
                Yii::$app->session->setFlash('error', 'Hibás felhasználónév vagy jelszó.');
            }
        }

        return $this->render('index', [
            'model' => $model,
            'signupModel' => $signupModel,
        ]);
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['site/index']);
        }

        $this->layout = 'main';

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirect(['site/index']);
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionSignup()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['site/choose']);
        }

        $signupModel = new \app\models\SignupForm();

        if ($signupModel->load(Yii::$app->request->post()) && $signupModel->signup()) {
            Yii::$app->session->setFlash('success', 'Sikeres regisztráció! Üdvözlünk a platformon.');
            return $this->redirect(['site/index']);
        } else {
            Yii::$app->session->setFlash('error', 'A regisztráció nem sikerült. Kérlek, ellenőrizd a megadott adatokat!');
            return $this->redirect(['site/index']);
        }
    }

    public function actionProfile()
    {
        $user = Yii::$app->user->identity;

        $myOffers = [];
        if (class_exists('\app\models\OfferRequest')) {
            $myOffers = \app\models\OfferRequest::find()
                ->where(['email' => $user->email])
                ->orWhere(['nev' => $user->username])
                ->orderBy(['id' => SORT_DESC])
                ->all();
        }

        return $this->render('profile', [
            'user' => $user,
            'model' => $user,
            'myOffers' => $myOffers,
        ]);
    }

    public function actionRolunk()
    {
        $this->layout = 'main';
        return $this->render('rolunk');
    }

    public function actionChoose()
    {
        $this->layout = 'main';      

        if (!Yii::$app->user->isGuest && Yii::$app->user->identity->username === 'admin') {
            return $this->redirect(['site/index']);
        }

        $cards = [
            [
                'url' => ['/ai/automation'],
                'badgeText' => 'Elérhető',
                'icon' => 'fas fa-cogs',
                'title' => 'AI Automatizáció',
                'description' => 'Ismerd meg az AI alapú munkafolyamat-optimalizációt, próbáld ki a fókuszált chatet és kérj ajánlatot!',
                'actionText' => 'Megnyitás',
            ],
            [
                'url' => ['/ai3d/landing'],
                'badgeText' => 'Bemutató',
                'icon' => 'fas fa-cube',
                'title' => '3D Rendszer',
                'description' => 'Ismerd meg a 3D-s automatizációs megoldásokat és funkciókat.',
                'actionText' => 'Megnyitás',
            ],
        ];

        foreach ($cards as $index => &$card) {
            $shortcutNumber = (string)($index + 1);
            $card['shortcut'] = $shortcutNumber;
            $card['shortcutKey'] = '[' . $shortcutNumber . ']';
        }
        unset($card);

        return $this->render('choose', [
            'cards' => $cards,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->redirect(['site/index']);
    }

    public function actionContact($type = null)
    {
        $this->layout = 'main';
        $model = new ContactForm();

        if ($type !== null && in_array($type, ['ai', '3d'])) {
            $model->tipus = $type; 
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if (class_exists('\app\models\Ajanlatkeres')) {
                $dbModel = new \app\models\Ajanlatkeres();
                $dbModel->nev = $model->name;
                $dbModel->email = $model->email;
                $dbModel->targy = $model->subject;
                $dbModel->leiras = $model->body;
                $dbModel->tipus = $model->tipus ?? $type; 

                if ($dbModel->save(false)) {
                    Yii::$app->session->setFlash('success', 'Köszönjük! Az ajánlatkérést elküldtük.');
                    return $this->refresh();
                }
            }
        }

        return $this->render('contact', [
            'model' => $model,
            'type'  => $type,
        ]);
    }

    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionRequestPasswordReset()
    {
        $this->layout = 'main';
        
        $model = new \app\models\PasswordResetRequestForm();
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Nézd meg az e-mail fiókodat! Elküldtük a további lépéseket.');
                return $this->redirect(['site/index']);
            } else {
                Yii::$app->session->setFlash('success', 'Ha a megadott e-mail cím szerepel a rendszerünkben, elküldtük a további lépéseket.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    public function actionResetPassword($token)
    {
        $this->layout = 'main';

        try {
            $model = new \app\models\ResetPasswordForm($token);
        } catch (\yii\base\InvalidArgumentException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
            return $this->redirect(['site/index']);
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'A jelszavadat sikeresen frissítettük! Most már bejelentkezhetsz az új jelszóval.');
            return $this->redirect(['site/index']);
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
}