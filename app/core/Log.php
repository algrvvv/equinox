<?php
/*
 * Copyright (c) 2024 p4xt3r. All rights reserved.
 */

namespace Imissher\Equinox\app\core;

use Imissher\Equinox\app\core\Facades\Facade;

/**
 * @method static info(string $message, bool $showFile = false): void
 * @method static error(string $message, bool $showFile = true): void
 * @method static debug(string $message, bool $showFile = true): void
 */
class Log extends Facade
{
    protected static function getFacadeRoot(): string
    {
        return "log";
    }
}
