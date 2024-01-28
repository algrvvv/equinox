<?php

namespace Imissher\Equinox\app\core\Facades;

use Imissher\Equinox\app\core\Facades\src\Router;

class Container
{
    private array $container;

    /**
     * Получение экземпляра класса по ключу из контейнера
     *
     * @param string $key
     * @return mixed
     */
    public function get(string $key): mixed
    {
        return $this->container[$key] ?? null;
    }

    /**
     * Добавление или обновление класса контейнера по ключу
     *
     * @param string $key
     * @param $value
     * @return void
     */
    public function set(string $key, $value): void
    {
        $this->container[$key] = $value;
    }

}
