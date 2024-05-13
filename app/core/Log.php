<?php
/*
 * Copyright (c) 2024 p4xt3r. All rights reserved.
 */

namespace Imissher\Equinox\app\core;

use Imissher\Equinox\app\core\Facades\Facade;

/**
 * @method info(string $message): void
 * @method error(string $message): void
 * @method debug(string $message): void
 */
class Log extends Facade
{
    protected static function getFacadeRoot(): string
    {
        return "log";
    }
}
