<?php

require_once __DIR__ . "/vendor/autoload.php";

use Imissher\Equinox\app\core\Application;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$config = [
    'db' => [
        'dsn' => $_ENV['DB_DRIVER'] . ":host=" . $_ENV['DB_HOST'] . ";post=" . $_ENV['DB_PORT'] . ";dbname=" . $_ENV['DB_NAME'],
        'user' => $_ENV['DB_USERNAME'],
        'password' => $_ENV['DB_PASSWORD']
    ],
    'master' => $argv,
    'display_error' => $_ENV['DISPLAY_ERROR']
];

$app = new Application(__DIR__, $config);
