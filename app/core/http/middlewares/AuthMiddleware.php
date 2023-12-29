<?php

namespace Imissher\Equinox\app\core\http\middlewares;

use Imissher\Equinox\app\core\Application;

class AuthMiddleware extends Middleware
{
    public function handler()
    {
        $isGuest = Application::isGuest();

        if ($isGuest) echo "ты гость";
    }
}
