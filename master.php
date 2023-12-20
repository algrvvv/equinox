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
    ]
];

/**
 * |--------------------------------------------------------------------------
 * | $argc -> number of arguments passed
 * | $argv -> the arguments passed
 * |--------------------------------------------------------------------------
 */

$params = explode(':', $argv[1]);

$command = $params[0] ?? ''; $param = $params[1] ?? '';

$app = new Application(dirname(__DIR__), $config);


if ($command === 'create') {
    echo match ($param) {
        'migration' => 'создание миграции',
        'controller' => 'создание контроллера',
        default => 'нет такого параметра',
    };
} elseif ($command === '-h' || $command === '--help'){
    echo "привет, это помощь";
} elseif ($command === 'migrate'){
    $app->db->applyMigration();
}
