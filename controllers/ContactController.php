<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\ContactForm;
use app\services\DashboardService;

/**
 * ContactController - Kizárólag a kapcsolatfelvételért és ajánlatkérésért felel (SRP).
 */
class ContactController extends Controller
{
    public $layout = 'main';

    private DashboardService $dashboardService;

    public function __construct($id, $module, DashboardService $dashboardService, $config = [])
    {
        $this->dashboardService = $dashboardService;
        parent::__construct($id, $module, $config);
    }

    /**
     * Kapcsolatfelvételi és ajánlatkérő űrlap kezelése
     */
    public function actionIndex($type = null)
    {
        $model = new ContactForm();

        if ($type !== null && in_array($type, ['ai', '3d'])) {
            $model->tipus = $type;
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($this->dashboardService->saveContactOffer($model, $type)) {
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