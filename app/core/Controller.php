<?php

namespace Imissher\Equinox\app\core;

class Controller
{
    public function render()
    {
        require_once Application::$ROOT_PATH . '/views/test.view.php';
    }
}