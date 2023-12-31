<?php

namespace Imissher\Equinox\app\core;

use Imissher\Equinox\app\core\Helpers\RouteTrait;

class Controller
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
}