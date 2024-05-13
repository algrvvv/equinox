<?php
/*
 * Copyright (c) 2024 p4xt3r. All rights reserved.
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default timezone for your application, which
    | will be used by the PHP date and date-time functions. We have gone
    | ahead and set this to a sensible default for you out of the box.
    |
    */

    'timezone' => env('APP_TIMEZONE', 'Europe/Moscow'),

    /*
    |--------------------------------------------------------------------------
    | Application log filename
    |--------------------------------------------------------------------------
    |
    | Name of the file intended for logging
    |
    */

    'log' => [
        'filename' => env('LOG_FILENAME', 'equinox')
    ],

    /*
    |--------------------------------------------------------------------------
    | Autoloaded Service Providers
    |--------------------------------------------------------------------------
    |
    | The service providers listed here will be automatically loaded on the
    | request to your application. Feel free to add your own services to
    | this array to grant expanded functionality to your applications.
    |
    */

    'providers' => [
        new \Imissher\Equinox\app\providers\AppProvider(),
        new \Imissher\Equinox\app\providers\CustomFacades()
    ]
];
