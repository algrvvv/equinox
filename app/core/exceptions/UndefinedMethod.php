<?php

namespace Imissher\Equinox\app\core\exceptions;

use Exception;

class UndefinedMethod extends Exception
{
    /**
     * Данное исключение необходимо для Master.php
     *
     * @var string $message
     */
    protected $message = "Данной команды не существует.\nПопробуйте воспользоваться `-h`";
}