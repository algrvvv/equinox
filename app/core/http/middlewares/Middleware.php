<?php

namespace Imissher\Equinox\app\core\http\middlewares;

use Imissher\Equinox\app\core\Application;
use Imissher\Equinox\app\core\Helpers\RouteTrait;
use Imissher\Equinox\app\core\http\Request;
use Imissher\Equinox\app\core\http\Route;
use Imissher\Equinox\app\core\Session;

abstract class Middleware
{
    use RouteTrait {
        RouteTrait::__construct as private __routeTraitConstruct;
    }

    protected Session $session;

    public function __construct()
    {
        $this->session = Application::$app->session;
        $this->__routeTraitConstruct();
    }

    protected function getUrl(): string
    {
        return Application::$app->request->getUrl();
    }

    abstract public function handler(string $url);

}