<?php

namespace Imissher\Equinox\app\core\exceptions;

class NotFoundException extends \Exception
{
    protected $code = 404;
    protected $message = "Страница не найдена";
}