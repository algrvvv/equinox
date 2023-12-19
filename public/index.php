<?php

require_once __DIR__ . "/../vendor/autoload.php";

use Imissher\Equinox\app\controllers\RegisterController;
use Imissher\Equinox\app\controllers\TestController;
use Imissher\Equinox\app\core\Application;

$app = new Application(dirname(__DIR__));

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

/*
|--------------------------------------------------------------------------
| Запуск приложения
|--------------------------------------------------------------------------
*/

$app->run();