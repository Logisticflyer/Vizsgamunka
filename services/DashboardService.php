<?php

namespace app\services;

use Yii;
use app\models\OfferRequest;
use app\models\Ajanlatkeres;
use app\models\AiKnowledge;

/**
 * Class DashboardService
 * 
 * Felelősség (SRP): A műszerfalhoz (KPI adatok), a profiloldali ajánlatkérésekhez 
 * és a kapcsolatfelvételi ajánlatok mentéséhez kapcsolódó üzleti logika kezelése.
 */
class DashboardService
{
    /**
     * Összegyűjti az adminisztrátori műszerfalhoz (KPI) szükséges adatokat.
     * 
     * @return array Az összegzett műszerfal adatok (összes ajánlat, legutóbbiak, tudásbázis)
     */
    public function getAdminDashboardData(): array
    {
        $totalOffers = 0;
        $recentOffers = [];

        if (class_exists(OfferRequest::class)) {
            $totalOffers = OfferRequest::find()->count();
            $recentOffers = OfferRequest::find()->orderBy(['id' => SORT_DESC])->limit(5)->all();
        } elseif (class_exists(Ajanlatkeres::class)) {
            $totalOffers = Ajanlatkeres::find()->count();
            $recentOffers = Ajanlatkeres::find()->orderBy(['id' => SORT_DESC])->limit(5)->all();
        }

        $totalKnowledge = 0;
        if (class_exists(AiKnowledge::class)) {
            $totalKnowledge = AiKnowledge::find()->count();
        }

        return [
            'totalOffers' => (int)$totalOffers,
            'recentOffers' => $recentOffers,
            'totalKnowledge' => (int)$totalKnowledge,
        ];
    }

    /**
     * Lekéri a megadott felhasználóhoz tartozó ajánlatkéréseket a profiloldalhoz.
     * 
     * @param object $user A bejelentkezett felhasználó entitása vagy objektuma
     * @return array A felhasználóhoz tartozó ajánlatkérések listája
     */
    public function getUserOffers(object $user): array
    {
        if (class_exists(OfferRequest::class)) {
            return OfferRequest::find()
                ->where(['email' => $user->email])
                ->orWhere(['nev' => $user->username])
                ->orderBy(['id' => SORT_DESC])
                ->all();
        } elseif (class_exists(Ajanlatkeres::class)) {
            return Ajanlatkeres::find()
                ->where(['email' => $user->email])
                ->orWhere(['nev' => $user->username])
                ->orderBy(['id' => SORT_DESC])
                ->all();
        }

        return [];
    }

    /**
     * Elmenti a kapcsolatfelvételi űrlapon keresztül beküldött ajánlatkérést.
     * 
     * @param object $model ContactForm modell
     * @param string|null $type Az ajánlatkérés opciója vagy típusa
     * @return bool Igaz, ha a mentés sikeres volt
     */
    public function saveContactOffer(object $model, ?string $type = null): bool
    {
        if (class_exists(Ajanlatkeres::class)) {
            $dbModel = new Ajanlatkeres();
            $dbModel->nev = $model->name;
            $dbModel->email = $model->email;
            $dbModel->targy = $model->subject;
            $dbModel->leiras = $model->body;
            $dbModel->tipus = $model->tipus ?? $type;

            return $dbModel->save(false);
        }

        return false;
    }
}