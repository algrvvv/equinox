<?php

namespace Imissher\Equinox\app\core\Helpers;

use Imissher\Equinox\app\core\Application;

trait RequestTrait
{
    public function getUrl(): string
    {
        return Application::$app->request->getUrl();
    }

    public function getMethod(): string
    {
        return Application::$app->request->method();
    }
}