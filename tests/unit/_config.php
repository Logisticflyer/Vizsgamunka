<?php

// 1. Composer autoloader betöltése (mivel a tests/unit-ból indulunk, 2 szintet kell lépni vissza a gyökérbe)
require_once __DIR__ . '/../../vendor/autoload.php';

// 2. Yii2 keretrendszer magfájljának betöltése
require_once __DIR__ . '/../../vendor/yiisoft/yii2/Yii.php';

return [
    // A Yii2 tesztalkalmazás kötelező azonosítói és alapbeállításai
    'id' => 'app-tests',
    // A basePath-nek a projekt gyökerére kell mutatnia (C:\xampp\htdocs\vizsgamunka)
    'basePath' => dirname(dirname(__DIR__)),
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'language' => 'hu-HU',

    // Komponensek
    'components' => [
        // Adatbázis-beállítás a Dockerben futó MySQL szerverhez
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=127.0.0.1;dbname=vizsgamunka;charset=utf8mb4',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8mb4',
        ],
        // Szükséges alapkomponensek a tesztkörnyezet stabil indulásához:
        'request' => [
            'cookieValidationKey' => 'test_key_for_codeception',
            'enableCsrfValidation' => false,
        ],
        'urlManager' => [
            'showScriptName' => true,
        ],
    ],
];