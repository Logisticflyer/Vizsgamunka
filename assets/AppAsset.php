<?php

/**
 * @link https://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license https://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    
    public $css = [
    ];

    public $js = [
        // 1. Először betöltjük a Three.js alapot a felhőből
        'https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js',
        // 2. Másodikként a te saját kódodat, ami már építhet a fenti alapra
        'js/three-init.js', 
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap5\BootstrapAsset',
    ];
    
    public function init()
    {
        parent::init();
        // Automatikusan időbélyeget (?v=1234567) rak a fájl végére, 
        // így ha módosítod a JS-t, a böngésző azonnal az újat tölti be!
        \Yii::$app->assetManager->appendTimestamp = true;
    }
}