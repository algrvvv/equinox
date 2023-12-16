<?php

namespace Imissher\Equinox\app\core\http;

class Request
{
    public function getUrl(): string
    {
        $url = $_SERVER['REQUEST_URI'] ?? '/';
        $pos_query = strpos($url, '?');

        if (!$pos_query) return $url;

        return substr($url, 0, $pos_query);
    }

    /**
     * Возвращает метод запроса
     *
     * @return string
     */
    public function method(): string
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    /**
     * @return bool
     */
    public function isGet(): bool
    {
        return $this->method() === 'get';
    }

    /**
     * @return bool
     */
    public function isPost(): bool
    {
        return $this->method() === 'post';
    }

    public function getBody()
    {
        //TODO написать функцию получения тела запроса
    }
}