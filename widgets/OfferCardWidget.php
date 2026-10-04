<?php

namespace app\widgets;

use yii\base\Widget;
use yii\helpers\Html;

/**
 * Class OfferCardWidget
 * 
 * Felelősség (SRP): Kizárólag egy adott ajánlatkérés kártya-alapú HTML megjelenítéséért felel.
 */
class OfferCardWidget extends Widget
{
    /**
     * @var object|array Az ajánlatkérés adatai (Ajanlatkeres vagy OfferRequest modell)
     */
    public $offer;

    /**
     * @var string Az ajánlat kártya témája/stílusa ('dark', 'light', 'primary')
     */
    public $theme = 'dark';

    /**
     * Widget inicializálása.
     */
    public function init()
    {
        parent::init();
        if ($this->offer === null) {
            $this->offer = (object)[
                'nev' => 'Nincs megadva',
                'email' => 'Nincs megadva',
                'targy' => 'Nincs tárgy',
                'leiras' => '',
                'tipus' => 'Általános',
            ];
        }
    }

    /**
     * A Widget futtatása és a kártya nézet renderelése.
     * 
     * @return string A kirajzolt HTML tartalom
     */
    public function run()
    {
        return $this->render('offer-card', [
            'offer' => $this->offer,
            'theme' => $this->theme,
        ]);
    }
}