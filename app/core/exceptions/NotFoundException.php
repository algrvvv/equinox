<?php

namespace Imissher\Equinox\app\core\exceptions;

use Exception;

class NotFoundException extends Exception
{
    protected $code = 404;
    protected $message = "Страница не найдена";
}