<?php

namespace Imissher\Equinox\app\core;

class Controller
{
    public function render(string $view, array $params = [])
    {
        return Application::$app->router->render($view, $params);
    }
}