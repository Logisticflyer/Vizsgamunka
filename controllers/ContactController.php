<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\ContactForm;
use app\services\DashboardService;
use app\services\ContactService;

/**
 * ContactController - Kizárólag a kapcsolatfelvételért és ajánlatkérésért felelős vezérlő (SRP).
 */
class ContactController extends Controller
{
    public $layout = 'main';

    private DashboardService $dashboardService;
    private ContactService $contactService;

    /**
     * Konstruktor alapú függőség-befecskendezés (Dependency Injection).
     *
     * @param string $id A vezérlő azonosítója
     * @param mixed $module A modul, amelyhez a vezérlő tartozik
     * @param DashboardService $dashboardService Az ajánlatkérések mentéséért felelős szerviz
     * @param ContactService $contactService Az e-mail küldésért felelős szerviz
     * @param array $config További konfigurációs paraméterek
     */
    public function __construct(
        $id,
        $module,
        DashboardService $dashboardService,
        ContactService $contactService,
        array $config = []
    ) {
        $this->dashboardService = $dashboardService;
        $this->contactService = $contactService;
        parent::__construct($id, $module, $config);
    }

    /**
     * Külső akciók deklarálása (pl. Captcha validáció a tesztekhez és az űrlaphoz).
     *
     * @return array
     */
    public function actions()
    {
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Kapcsolatfelvételi és ajánlatkérő űrlap kezelése.
     *
     * @param string|null $type Az ajánlatkérés típusa ('ai', '3d' vagy null)
     * @return string|\yii\web\Response
     */
    public function actionIndex($type = null)
    {
        $model = new ContactForm();

        if ($type !== null && in_array($type, ['ai', '3d'])) {
            $model->tipus = $type;
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $adminEmail = Yii::$app->params['adminEmail'] ?? 'admin@example.com';

            $offerSaved = $this->dashboardService->saveContactOffer($model, $type);
            $emailSent = $this->contactService->sendContactEmail($model, $adminEmail);

            if ($offerSaved || $emailSent) {
                Yii::$app->session->setFlash('success', 'Köszönjük! Az ajánlatkérést elküldtük.');
                return $this->refresh();
            }
        }

        return $this->render('contact', [
            'model' => $model,
            'type'  => $type,
        ]);
    }
}