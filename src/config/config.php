<?php

require_once __DIR__ . '/../vendor/autoload.php';

return [
    'db' => [
        'host' => $_ENV['DB_HOST'] ?? 'db',
        'name' => $_ENV['DB_NAME'] ?? 'gamevault',
        'user' => $_ENV['DB_USER'] ?? 'gamer',
        'pass' => $_ENV['DB_PASS'] ?? 'password',
    ],
    'app' => [
        'url' => $_ENV['APP_URL'] ?? 'http://localhost:8080',
        'env' => $_ENV['APP_ENV'] ?? 'production',
    ]
];
