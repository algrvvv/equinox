<?php

namespace Imissher\Equinox\app\database\migrations;

use Imissher\Equinox\app\core\database\Migration;
use Imissher\Equinox\app\core\database\Schema;

class test_migration extends Migration
{
    public function up(): void
    {
        $this->table->create('test', function () {
            $this->table->id();
            $this->table->string('email')->unique();
            $this->table->string('password');
            $this->table->timestamps();
        });
    }

    public function down()
    {
        $this->table->dropTable('test');
    }
}