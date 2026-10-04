<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\services\DashboardService;
use app\models\ContactForm;
use app\models\LoginForm;

/**
 * SiteController - Kizárólag a kezdőlapért, műszerfalért, bejelentkezésért, kapcsolati oldalért és statikus tartalmakért felel (SRP).
 */
class SiteController extends Controller
{
    public $layout = 'main';

    private DashboardService $dashboardService;

    public function __construct($id, $module, DashboardService $dashboardService, $config = [])
    {
        $this->dashboardService = $dashboardService;
        parent::__construct($id, $module, $config);
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['choose', 'profile', 'logout'],
                'rules' => [
                    [
                        'actions' => ['choose', 'profile', 'logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

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
     * Kezdőlap és Adminisztrátori Dashboard
     */
    public function actionIndex()
    {
        if (!Yii::$app->user->isGuest) {
            if (Yii::$app->user->identity->username === 'admin') {
                $dashboardData = $this->dashboardService->getAdminDashboardData();
                return $this->render('index', $dashboardData);
            }
            return $this->redirect(['site/choose']);
        }

        return $this->render('index');
    }

    /**
     * Bejelentkezési akció (Login)
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Kijelentkezési akció (Logout)
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Kapcsolati oldal és űrlap kezelése (Contact)
     */
    public function actionContact($type = null)
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'] ?? 'admin@example.com')) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
            'type' => $type,
        ]);
    }

    /**
     * Felhasználói profil oldal
     */
    public function actionProfile()
    {
        $user = Yii::$app->user->identity;
        $myOffers = $this->dashboardService->getUserOffers($user);

        return $this->render('profile', [
            'user' => $user,
            'model' => $user,
            'myOffers' => $myOffers,
        ]);
    }

    /**
     * Rendszer- / Témaválasztó oldal
     */
    public function actionChoose()
    {
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

        return $this->render('choose', ['cards' => $cards]);
    }

    public function actionRolunk()
    {
        return $this->render('rolunk');
    }

    public function actionAbout()
    {
        return $this->render('about');
    }
}