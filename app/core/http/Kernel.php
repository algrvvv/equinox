<?php

namespace Imissher\Equinox\app\core\http;

use Imissher\Equinox\app\core\Helpers\RequestTrait;
use Imissher\Equinox\app\core\http\middlewares\AuthMiddleware;
use Imissher\Equinox\app\core\http\middlewares\GuestMiddleware;
use Imissher\Equinox\app\core\http\middlewares\Middleware;

class Kernel
{
    use RequestTrait;

    protected array $middlewares = [
        'auth' => [
            AuthMiddleware::class
        ],
        'guest' => [
            GuestMiddleware::class
        ]
    ];

    protected array $routes = [];

    public function handler(string|array $rule, string $url, string $method): void
    {
        /*
         * добавление данных типа [middleware => [...$links]];
         */
        if (is_array($rule)) {
            foreach ($rule as $r) {
                if (!isset($this->routes[$r])) $this->routes[$r] = [];
                $this->routes[$r][$method][] = $url;
            }
        } elseif (is_string($rule)) {
            if (!isset($this->routes[$rule])) $this->routes[$rule] = [];
            $this->routes[$rule][$method][] = $url;
        }
    }

    public function start(): void
    {
        foreach ($this->routes as $rules => $middlewares) {
            if (!isset($middlewares[$this->getMethod()])) continue;

            if (count($middlewares[$this->getMethod()]) === 1) {
                $url = $middlewares[$this->getMethod()][0];
                if ($url != $this->getUrl()) continue;

                $this->launching_handlers($rules, $url);
            } else {
                foreach ($middlewares[$this->getMethod()] as $url) {
                    if ($url != $this->getUrl()) continue;

                    $this->launching_handlers($rules, $url);
                }
            }

        }
    }

    private function launching_handlers(string $rule, string $url): void
    {
        /** @var Middleware $middleware */
        $middlewares = $this->middlewares[$rule];
        if (count($middlewares) === 1) {
            $middleware = new $middlewares[0]();
            $middleware->handler($url);
        } else {
            foreach ($middlewares as $middleware) {
                $middleware = new $middleware();
                $middleware->handler($url);
            }
        }
    }
}
