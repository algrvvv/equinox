<?php

require_once __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . "/../app/core/Helpers/helpers.php";

/**
 * Проверка на совместимость версий php
 */
if ((float)phpversion() < 8.1) {
    echo "Require a PHP version >= 8.1.0 You are running " . phpversion();
    exit;
}

use Imissher\Equinox\app\controllers\LoginController;
use Imissher\Equinox\app\controllers\RegisterController;
use Imissher\Equinox\app\controllers\ProfileController;
use Imissher\Equinox\app\controllers\TestController;
use Imissher\Equinox\app\core\Application;

/**
 * |--------------------------------------------------------------------------
 * | $config => Массив полученных данных из `.env` файла, которые нужны
 * | для конфигурации приложения.
 * |--------------------------------------------------------------------------
 */
$config = [
    'db' => [
        'driver' => env('DB_DRIVER'),
        'dsn' => "host=" . env('DB_HOST') . ";port=" . env('DB_PORT') . ";dbname=" . env('DB_NAME'),
        'user' => env('DB_USERNAME'),
        'password' => env('DB_PASSWORD')
    ],
    'master' => [false],
    'display_error' => env('DISPLAY_ERROR'),
    'app_version' => env('APP_VERSION')
];

/*
|--------------------------------------------------------------------------
| Запуск приложения
|--------------------------------------------------------------------------
*/

$app = new Application(dirname(__DIR__), $config);

require_once "./../routes/web.php";

$app->run();
