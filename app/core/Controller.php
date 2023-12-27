<?php

namespace Imissher\Equinox\app\core;

use JetBrains\PhpStorm\NoReturn;

class Controller
{
    private http\Route $route;
    public function __construct()
    {
        $this->route = Application::$app->route;
    }

    public function render(string $view, array $params = []): false|array|string
    {
        return $this->route->render($view, $params);
    }

    public function redirect(string $url): static
    {
        $this->route->redirect($url);
        return $this;
    }

    #[NoReturn] public function with(string $sub, string $mess): void
    {
        $this->route->with($sub, $mess);
        exit;
    }
}