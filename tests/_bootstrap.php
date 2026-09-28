<?php

defined('YII_ENV') or define('YII_ENV', 'test');
defined('YII_DEBUG') or define('YII_DEBUG', true);

// 1. Composer autoloader betöltése
require_once __DIR__ . '/../vendor/autoload.php';

// 2. Yii2 keretrendszer magfájljának betöltése
require_once __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

// 3. Teszt gyökér alias regisztrálása
Yii::setAlias('@tests', __DIR__);