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

$app = new Application(dirname(__DIR__), $config);

/*
|--------------------------------------------------------------------------
| Роуты, которые сайт будет обрабатывать
|--------------------------------------------------------------------------
|
| Поддержка GET, POST, PUT, PATCH, DELETE запросов.
|
*/

$app->route->get('/', 'home');

$app->route->get('/profile', [ProfileController::class, 'profile'])->middleware('auth');

$app->route->get('/register', [RegisterController::class, 'index'])->middleware('guest');
$app->route->post('/register', [RegisterController::class, 'store'])->middleware('guest');

$app->route->get('/login', [LoginController::class, 'index'])->middleware('guest');
$app->route->post('/login', [LoginController::class, 'login'])->middleware('guest');

$app->route->post('/logout', [LoginController::class, 'logout']);

$app->route->get('/test/{id}/{name}', [TestController::class, 'index']);

/*
|--------------------------------------------------------------------------
| Запуск приложения
|--------------------------------------------------------------------------
*/

$app->run();
