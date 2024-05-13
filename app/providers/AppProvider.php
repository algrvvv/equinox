<?php
/*
 * Copyright (c) 2024 p4xt3r. All rights reserved.
 */

namespace Imissher\Equinox\app\providers;

use Imissher\Equinox\app\core\exceptions\FailedToOpenStream;
use Imissher\Equinox\app\core\interfaces\Provider;

class AppProvider implements Provider
{
    function boot(): void
    {
        date_default_timezone_set(config('app', 'timezone'));
    }
}
