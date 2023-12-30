<?php

namespace Imissher\Equinox\app\core\http\middlewares;

use Imissher\Equinox\app\core\Application;
use Imissher\Equinox\app\core\Helpers\RequestTrait;
use Imissher\Equinox\app\core\Helpers\RouteTrait;
use Imissher\Equinox\app\core\Session;

abstract class Middleware
{
    use RequestTrait, RouteTrait {
        RouteTrait::__construct as private __routeTraitConstruct;
    }

    protected Session $session;

    public function __construct()
    {
        $this->session = Application::$app->session;
        $this->__routeTraitConstruct();
    }


    abstract public function handler(string $url);

}