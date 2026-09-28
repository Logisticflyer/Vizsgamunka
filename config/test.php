<?php

// 1. Composer autoloader betöltése (osztályok automatikus betöltéséhez)
require_once __DIR__ . '/../vendor/autoload.php';

// 2. Yii2 keretrendszer magfájljának betöltése (ez hozza létre a "Yii" osztályt)
require_once __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/test_db.php';

/**
 * Alkalmazás konfiguráció a teszt környezethez (helyi XAMPP / 127.0.0.1 kényszerítéssel)
 */
return yii\helpers\ArrayHelper::merge(
    require __DIR__ . '/web.php',
    [
        'id' => 'basic-tests',
        'basePath' => dirname(__DIR__),
        'aliases' => [
            '@bower' => '@vendor/bower-asset',
            '@npm'   => '@vendor/npm-asset',
        ],
        'language' => 'en-US',

        // =========================================================================
        // REPOSITORY & DI KONTÉNER BEÁLLÍTÁSA A TESZTKÖRNYEZETBEN (SRP)
        // =========================================================================
        'container' => [
            'definitions' => [
                \app\repositories\UserRepositoryInterface::class => \app\repositories\UserRepository::class,
            ],
        ],

        'components' => [
            // A teszt adatbázis-kapcsolat átvétele a test_db.php fájlból
            'db' => $db,
            'mailer' => [
                'class' => \yii\symfonymailer\Mailer::class,
                'viewPath' => '@app/mail',
                'useFileTransport' => true,
                'messageClass' => 'yii\symfonymailer\Message'
            ],
            'assetManager' => [
                'basePath' => __DIR__ . '/../web/assets',
            ],
            'urlManager' => [
                'showScriptName' => true,
            ],
            'user' => [
                'identityClass' => 'app\models\User',
            ],
            'request' => [
                'cookieValidationKey' => 'test',
                'enableCsrfValidation' => false,
            ],
        ],
        'params' => $params,
    ]
);