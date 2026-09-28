<?php

/**
 * Teszt adatbázis konfiguráció a helyi XAMPP / Windows környezethez.
 */
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=127.0.0.1;dbname=vizsgamunka;charset=utf8mb4',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8mb4',
    
    // Séma gyorsítótár letiltása a tesztelési hibák elkerülésére
    'enableSchemaCache' => false,
];