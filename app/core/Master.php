<?php

namespace Imissher\Equinox\app\core;

class Master
{

    public function __construct(array $config)
    {
        print_r($config);
        $command = explode(':', $config[1]);
        $this->{$command[0]}($command[1]);

    }

    public function create(string $type)
    {
        echo "create $type";
    }
}