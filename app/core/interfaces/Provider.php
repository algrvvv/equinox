<?php
/*
 * Copyright (c) 2024 p4xt3r. All rights reserved.
 */

namespace Imissher\Equinox\app\core\interfaces;

use Imissher\Equinox\app\core\exceptions\FailedToOpenStream;

interface Provider
{
    /**
     * Метод для запуска настроек приложения на стадии его сборки и запуска
     * @throws FailedToOpenStream
     * @return void
     */
    function boot(): void;
}
