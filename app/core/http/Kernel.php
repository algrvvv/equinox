<?php

namespace Imissher\Equinox\app\core\http;

use Imissher\Equinox\app\core\Helpers\RequestTrait;
use Imissher\Equinox\app\core\http\middlewares\AuthMiddleware;
use Imissher\Equinox\app\core\http\middlewares\Middleware;

class Kernel
{
    use RequestTrait;
    protected array $middlewares = [
        'auth' => [
            AuthMiddleware::class
        ],
    ];

    protected array $routes = [];

    public function handler(string|array $rule, string $url): void
    {
        /**
         * добавление данных типа [middleware => [...$links]];
         */
        if(is_array($rule)){
            foreach ($rule as $r){
                if(!isset($this->routes[$r])) $this->routes[$r] = [];
                $this->routes[$r][] = $url;
            }
        } elseif(is_string($rule)){
            if(!isset($this->routes[$rule])) $this->routes[$rule] = [];
            $this->routes[$rule][] = $url;
        }

        /**
         * Запуск нужного класса в зависимости от правила.
         */
        foreach ($this->middlewares as $rules => $middlewares){
            if($rule === $rules){
                /** @var Middleware $middleware */
                if(count($middlewares) === 1){
                    if($this->getUrl() != $url) return;
                    $middleware = new $middlewares[0]();
                    $middleware->handler($url);
                } else {
                    foreach ($middlewares as $middleware){
                        if($this->getUrl() != $url) return;
                        $middleware = new $middleware();
                        $middleware->handler($url);
                    }
                }
            }
        }
    }
}
