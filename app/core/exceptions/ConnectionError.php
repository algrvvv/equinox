<?php

namespace Imissher\Equinox\app\core\exceptions;

use Exception;

class ConnectionError extends Exception
{
    protected $code = 503;
    protected $message = "Ошибка подключения базы данных";
}
