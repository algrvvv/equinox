<?php

namespace Imissher\Equinox\app\core\Facades;

use Imissher\Equinox\app\core\Application;

abstract class Facade
{
    private static Container $container;

    abstract protected static function getFacadeRoot(): string;

    private static function setContainer(): void
    {
        if (!isset(self::$container)) {
            self::$container = Application::$app->container;
        }
    }

    public static function __callStatic(string $method, array $args = [])
    {
        self::setContainer();
        $instance = self::$container->get(static::getFacadeRoot());
        $result = $instance->$method(...$args);
        self::$container->set(static::getFacadeRoot(), $instance);
        return $result;
    }
}
