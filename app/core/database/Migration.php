<?php

namespace Imissher\Equinox\app\core\database;

class Migration
{
    protected Schema $table;
    public function __construct()
    {
        $this->table = new Schema();
    }
}