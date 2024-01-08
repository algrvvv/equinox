<?php

namespace Imissher\Equinox\app\core\exceptions;

use Exception;

class ControllerError extends Exception
{
    protected $code = 500;
    protected $message = "Ошибка при работе с контроллером";
}
