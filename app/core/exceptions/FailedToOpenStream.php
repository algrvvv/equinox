<?php

namespace Imissher\Equinox\app\core\exceptions;

use Exception;

class FailedToOpenStream extends Exception
{
    public function __construct(string $file)
    {
        parent::__construct("\033[0;31mFailed to open file /app/core/config/$file.php\033[0m", 500);
    }
}