<?php

namespace Imissher\Equinox\app\core\exceptions;

use Exception;

class ReceivingData extends Exception
{
    protected $code = 400;
    protected $message = "Возникла ошибка при получении данных";
}