<?php

namespace Imissher\Equinox\app\core\exceptions;

use Exception;

class MigrationError extends Exception
{
    protected $message = "Произошла ошибка во время работы миграцией";
}