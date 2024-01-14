<?php

use Imissher\Equinox\app\core\Application;


if (!function_exists('redirect')) {
    function redirect(string $url): void
    {
        $route = Application::$app->route;
        $route->redirect($url);
    }
}

if (!function_exists('render')) {
    function render(string $view, array $params = []): false|array|string
    {
        $route = Application::$app->route;
        return $route->render($view, $params);
    }
}

if (!function_exists('show')) {
    function show(mixed $data): void
    {
        echo "<pre>";
        print_r($data);
        echo "</pre>";
    }
}
