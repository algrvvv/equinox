<?php

namespace Imissher\Equinox\app\core\database;

class Schema
{
    private string $query;
    private array $variables = [];

    public function create(string $db_name, $callback): void
    {
        $this->query = "CREATE TABLE `$db_name`";
        call_user_func($callback);
        print_r($this->variables);
        //TODO продолжаем тут и работаем над валидацией полученного запроса и его выполнения
    }

    public function dropTable(string $table): void
    {
        $this->query = 'DROP TABLE `$table`';
    }

    private function setVariables(string $name, string $structure): void
    {
        $this->variables[$name] = $structure;
    }

    public function id(string $id = 'id'): void
    {
        $this->setVariables($id, "INT AUTO_INCREMENT PRIMARY KEY");
    }

    public function string(string $string, int $length = 255, bool $not_null = true): void
    {
        $not_null = $not_null ? 'NOT NULL' : '';
        $this->setVariables($string, "VARCHAR($length) $not_null");
    }

    public function timestamps(string $timestamp = 'created_at', bool $default = true): void
    {
        $default = $default ? "DEFAULT CURRENT_TIMESTAMP" : '';
        $this->setVariables($timestamp, "TIMESTAMP $default");
    }
}