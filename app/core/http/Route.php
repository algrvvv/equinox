<?php

namespace Imissher\Equinox\app\core\http;

use Imissher\Equinox\app\core\Facades\Facade;

/**
 * @method get(string $route, mixed $callback): static
 * @method post(string $route, mixed $callback): static
 * @method put(string $route, mixed $callback): static
 * @method patch(string $route, mixed $callback): static
 * @method middleware(string|array $rule): false|static
 */

class Route extends Facade
{
    protected static function getFacadeRoot(): string
    {
        return "router";
    }
}