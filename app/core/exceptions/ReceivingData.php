<?php

namespace Imissher\Equinox\app\core\exceptions;

class ReceivingData extends \Exception
{
    protected $code = 400;
    protected $message = "Возникла ошибка при получении данных";
}