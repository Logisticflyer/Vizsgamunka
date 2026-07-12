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
        'css/site.css',
    ];

    public $js = [
        'js/three-init.js', 
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap5\BootstrapAsset',
    ];

    // Ez a Yii2 beépített trükkje: automatikusan egy egyedi verziószámot (?v=...) 
    // pakol a JS és CSS fájlok mögé, így a böngésző SOHA nem fogja a régi, elmentett kódot futtatni!
    public $publishOptions = [
        'forceCopy' => true,
    ];
    
    public function init()
    {
        parent::init();
        // Bekapcsoljuk a verziókövetést a cache-elés ellen
        \Yii::$app->assetManager->appendTimestamp = true;
    }
}