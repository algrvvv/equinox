<?php

namespace Imissher\Equinox\app\core\http\middlewares;

use Imissher\Equinox\app\core\Application;

class AuthMiddleware extends Middleware
{
    public function handler(string $url): void
    {
        $isGuest = Application::isGuest();

        if ($isGuest)  redirect('/');
    }
}
