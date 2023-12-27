<?php

require_once __DIR__ . "/../vendor/autoload.php";

use Imissher\Equinox\app\controllers\LoginController;
use Imissher\Equinox\app\controllers\RegisterController;
use Imissher\Equinox\app\controllers\TestController;
use Imissher\Equinox\app\core\Application;

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

/**
|--------------------------------------------------------------------------
| $config => Массив полученных данных из `.env` файла, которые нужны
| для конфигурации приложения.
|--------------------------------------------------------------------------
*/
$config = [
    'db' => [
        'dsn'  => $_ENV['DB_DRIVER'].":host=".$_ENV['DB_HOST'].";post=".$_ENV['DB_PORT'].";dbname=".$_ENV['DB_NAME'],
        'user' => $_ENV['DB_USERNAME'],
        'password' => $_ENV['DB_PASSWORD']
    ],
    'master' => [false]
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

$app->route->get('/call', function (){
    return "<br>hello from callback<br>";
});

$app->route->get('/', 'home');

$app->route->get('/test', [TestController::class, 'test']);

$app->route->get('/register', [RegisterController::class, 'index']);
$app->route->post('/register', [RegisterController::class, 'store']);

//TODO user->login -> route, controller, session, login (in app) + (isGuest etc.)

$app->route->get('/login', [LoginController::class, 'index']);
$app->route->post('/login', [LoginController::class, 'login']);

/*
|--------------------------------------------------------------------------
| Запуск приложения
|--------------------------------------------------------------------------
*/

$app->run();