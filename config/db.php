<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => sprintf(
        'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
        $_ENV['DB_HOST'] ?? '127.0.0.1',
        $_ENV['DB_PORT'] ?? '3306',
        $_ENV['DB_NAME'] ?? 'vizsgamunka'
    ),
    'username' => $_ENV['DB_USER'] ?? 'root',
    'password' => $_ENV['DB_PASS'] ?? '',
    'charset' => 'utf8mb4',

    // Schema cache kikapcsolva marad fejlesztői környezetben
    // 'enableSchemaCache' => true,
    // 'schemaCacheDuration' => 3600,
    // 'schemaCache' => 'cache',
];