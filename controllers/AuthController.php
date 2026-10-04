<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\SignupForm;
use app\models\PasswordResetRequestForm;
use app\models\ResetPasswordForm;
use app\services\UserService;

/**
 * AuthController - Kizárólag az autentikációért és jelszókezelésért felelős vezérlő (SRP).
 */
class AuthController extends Controller
{
    public $layout = 'main';

    private UserService $userService;

    /**
     * Konstruktor alapú függőség-befecskendezés (Dependency Injection).
     *
     * @param string $id A vezérlő azonosítója
     * @param mixed $module A modul, amelyhez a vezérlő tartozik
     * @param UserService $userService A felhasználói üzleti logikát kezelő szerviz
     * @param array $config További konfigurációs paraméterek
     */
    public function __construct($id, $module, UserService $userService, array $config = [])
    {
        $this->userService = $userService;
        parent::__construct($id, $module, $config);
    }

    /**
     * Viselkedési szabályok (Bejelentkezett felhasználók szűrése, HTTP metódusok).
     *
     * @return array
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
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
     * Bejelentkezés kezelése.
     *
     * @return string|\yii\web\Response
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['site/index']);
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirect(['site/index']);
        }

        $model->password = '';
        return $this->render('login', ['model' => $model]);
    }

    /**
     * Regisztráció kezelése a UserService segítségével.
     *
     * @return string|\yii\web\Response
     */
    public function actionSignup()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['site/choose']);
        }

        $signupModel = new SignupForm();

        if ($signupModel->load(Yii::$app->request->post())) {
            $user = $this->userService->register($signupModel);
            if ($user !== null) {
                Yii::$app->session->setFlash('success', 'Sikeres regisztráció! Üdvözlünk a platformon.');
                return $this->redirect(['auth/login']);
            }
            Yii::$app->session->setFlash('error', 'A regisztráció nem sikerült. Kérlek, ellenőrizd a megadott adatokat!');
        }

        return $this->render('signup', ['model' => $signupModel]);
    }

    /**
     * Kijelentkezés kezelése.
     *
     * @return \yii\web\Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->redirect(['site/index']);
    }

    /**
     * Jelszó-visszaállítási kérelem kezelése.
     *
     * @return string|\yii\web\Response
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Nézd meg az e-mail fiókodat! Elküldtük a további lépéseket.');
                return $this->redirect(['auth/login']);
            }
            Yii::$app->session->setFlash('success', 'Ha a megadott e-mail cím szerepel a rendszerünkben, elküldtük a további lépéseket.');
        }

        return $this->render('requestPasswordResetToken', ['model' => $model]);
    }

    /**
     * Jelszó beállítása token alapján.
     *
     * @param string $token A jelszó-visszaállító token
     * @return string|\yii\web\Response
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (\yii\base\InvalidArgumentException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
            return $this->redirect(['site/index']);
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'A jelszavadat sikeresen frissítettük! Most már bejelentkezhetsz.');
            return $this->redirect(['auth/login']);
        }

        return $this->render('resetPassword', ['model' => $model]);
    }
}