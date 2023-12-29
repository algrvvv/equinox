<?php

namespace Imissher\Equinox\app\core\http;

use Imissher\Equinox\app\core\http\middlewares\AuthMiddleware;
use Imissher\Equinox\app\core\http\middlewares\Middleware;

class Kernel
{
    protected array $middlewares = [
        'auth' => [
            AuthMiddleware::class
        ],
    ];

    public function __construct(string $rule)
    {
        //TODO сделать отслеживание конкретного роута
        foreach ($this->middlewares as $rules => $middlewares){
            if($rule === $rules){
                /** @var Middleware $middleware */
                if(count($middlewares) === 1){
                    $middleware = new $middlewares[0]();
                    $middleware->handler();
                } else {
                    foreach ($middlewares as $middleware){
                        $middleware = new $middleware();
                        $middleware->handler();
                    }
                }
            }
        }
    }
}
