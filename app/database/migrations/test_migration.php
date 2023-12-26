<?php

namespace Imissher\Equinox\app\database\migrations;

use Imissher\Equinox\app\core\database\Migration;

class test_migration extends Migration
{
    public function up()
    {
        return $this->table->create('test', function () {
            $this->table->id();
            $this->table->string('email')->unique();
            $this->table->string('password');
            $this->table->timestamps();
        });
    }

    public function drop()
    {
        return $this->table->dropTable('test');
    }
}