<?php

namespace Imissher\Equinox\app\core\http;

class Response
{
    /**
     * Изменение статуса ответа
     *
     * @param int $code
     * @return void
     */
    public function setResponseCode(int $code): void
    {
        http_response_code($code);
    }
}