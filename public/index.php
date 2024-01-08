<?php

require_once __DIR__ . "/../vendor/autoload.php";

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

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

/**
 * |--------------------------------------------------------------------------
 * | $config => Массив полученных данных из `.env` файла, которые нужны
 * | для конфигурации приложения.
 * |--------------------------------------------------------------------------
 */
$config = [
    'db' => [
        'driver' => $_ENV['DB_DRIVER'],
        'dsn' => "host=" . $_ENV['DB_HOST'] . ";port=" . $_ENV['DB_PORT'] . ";dbname=" . $_ENV['DB_NAME'],
        'user' => $_ENV['DB_USERNAME'],
        'password' => $_ENV['DB_PASSWORD']
    ],
    'master' => [false],
    'display_error' => $_ENV['DISPLAY_ERROR'],
    'app_version' => $_ENV['APP_VERSION']
];

$app = new Application(dirname(__DIR__), $config);

/*
|--------------------------------------------------------------------------
| Роуты, которые сайт будет обрабатывать
|--------------------------------------------------------------------------
|
| Поддержка GET, POST запросов.
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
