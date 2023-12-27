<?php
namespace Imissher\Equinox\app\database\migrations;

use Imissher\Equinox\app\core\database\Migration;

class m081328_231227_users extends Migration
{
    public function up(): bool|string
    {
        return $this->table->create('users', function () {
            $this->table->id();
            $this->table->string('login');
            $this->table->string('email')->unique();
            $this->table->string('password');
            $this->table->timestamps();
        });
    }

    public function drop(): false|int
    {
        return $this->table->dropTable('users');
    }
}