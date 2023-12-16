<?php

require_once __DIR__ . "/../vendor/autoload.php";

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

$app->router->get('/', function (){
    return "<br>home page<br>";
});

$app->router->get('/render', 'home');

$app->router->get('/test', [TestController::class, 'test']);

/*
|--------------------------------------------------------------------------
| Запуск приложения
|--------------------------------------------------------------------------
*/

$app->run();