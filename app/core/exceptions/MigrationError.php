<?php

namespace Imissher\Equinox\app\core\exceptions;

class MigrationError extends \Exception
{
    protected $message = "Произошла ошибка во время работы миграцией";
}