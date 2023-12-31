<?php

namespace Imissher\Equinox\app\core\http;

use Imissher\Equinox\app\core\Application;

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

    /**
     * @return array
     */
    public function getBody(): array
    {
        $body = [];

        if($this->method() === 'get'){
            foreach ($_GET as $key => $value){
                $body[$key] =filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }

        if($this->method() === 'post'){
            foreach ($_POST as $key => $value){
                $body[$key] =filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }

        return $body;
    }
}