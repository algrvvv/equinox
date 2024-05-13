<?php
/*
 * Copyright (c) 2024 p4xt3r. All rights reserved.
 */

namespace Imissher\Equinox\app\providers;

use Imissher\Equinox\app\core\interfaces\Provider;

class CustomFacades implements Provider
{
    /**
     * Добавления своих фасадов
     *
     * @return void
     */
    function boot(): void
    {
        // Application::$app->container->set('someClass', new SomeClass());
    }
}
