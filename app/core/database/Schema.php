<?php

namespace Imissher\Equinox\app\core\database;

use Exception;
use Imissher\Equinox\app\core\Application;
use Imissher\Equinox\app\core\exceptions\FailedToOpenStream;
use Imissher\Equinox\app\core\Helpers\MessageLogTrait;
use JetBrains\PhpStorm\NoReturn;

class Schema
{
    use MessageLogTrait;

    private string $query;
    private array $variables = [];
    private bool $hstore_status = false;

    /**
     * Создание миграции для (SQL)
     *
     * @param string $db_name
     * @param $callback
     * @return bool|string Возвращает `false` в случае ошибки. Иначе возвращает `db_name`
     */
    public function create(string $db_name, $callback): bool|string
    {
        $this->messageLog("Начало переноса `$db_name`");
        $this->query = "CREATE TABLE $db_name";
        call_user_func($callback);
        if (!empty($this->variables)) {
            try {
                $this->query_exec();
                $this->messageLog("Перенос `$db_name` успешно завершен");
                return $db_name;
            } catch (Exception $e) {
                $this->messageLog("Произошла ошибка при переносе `$db_name` : " . $e->getMessage());
                return false;
            }
        } else {
            $this->messageLog("Ошибка миграции: Отсутствуют поля для таблицы.");
            return false;
        }
    }

    /**
     * Подготовка запрос для (SQL)
     *
     * @return false|int
     */
    private function query_exec(): false|int
    {
        $structure = "";
        foreach ($this->variables as $key => $value) {
            $structure .= "$key $value, ";
        }
        $query = $this->query . " ( " . trim($structure, ', ') . ")";
        $this->query = $query;
        $db = Application::$app->db;
        return $db->pdo->exec($this->query);
    }

    /**
     * Удаление таблицы для (SQL)
     *
     * @param string $table
     * @return false|int
     */
    public function dropTable(string $table): false|int
    {
        $this->query = "DROP TABLE $table";
        $db = Application::$app->db;
        return $db->pdo->exec($this->query);
    }

    /**
     * Назначение полей
     *
     * @param string $name
     * @param string $structure
     * @return void
     */
    private function setVariables(string $name, string $structure): void
    {
        $this->variables[$name] = $structure . " NOT NULL";
    }

    /**
     * Создание переменной `id` (SQL)
     *
     * @param string $id
     * @return $this
     * @throws FailedToOpenStream
     */
    public function id(string $id = 'id'): static
    {
        $driver = Application::$app->db->db_driver;
        $structure = config('database', "autoIncrement.$driver");
        $this->setVariables($id, $structure);
        return $this;
    }

    /**
     * @param string $id
     * @return $this
     */
    public function bigserial(string $id = 'id'): static
    {
        $this->setVariables($id, "BIGSERIAL PRIMARY KEY NOT NULL");
        return $this;
    }

    /**
     * Создание переменной типа `string` (SQL)
     *
     * @param string $string Название колонки
     * @param int $length Максимальная допустимая длина
     * @return $this
     */
    public function string(string $string, int $length = 255): static
    {
        $this->setVariables($string, "VARCHAR($length)");
        return $this;
    }

    /**
     * Функция для создания переменной типа `integer`
     *
     * @throws FailedToOpenStream
     */
    public function integer(string $int): static
    {
        $this->setVariables($int, config('database', "integer." . env('DB_DRIVER')));
        return $this;
    }

    /**
     * Функция для создания переменной типа `float`
     *
     * @param string $float
     * @return $this
     * @throws FailedToOpenStream
     */
    public function float(string $float): static
    {
        $this->setVariables($float, config('database', "float." . env('DB_DRIVER')));
        return $this;
    }

    public function date(string $date): static
    {
        $this->setVariables($date, "DATE");
        return $this;
    }

    /**
     * Функция для создания переменной типа `text` (SQL)
     *
     * @param string $text
     * @return $this
     */
    public function text(string $text): static
    {
        $this->setVariables($text, "TEXT");
        return $this;
    }

    /**
     * Создание переменной типа `timestamp` (SQL)
     *
     * @param string $timestamp Название колонки. По дефолту - `created_at`
     * @param bool $default Назначение дефолтного значения
     * @return $this
     */
    public function timestamps(string $timestamp = 'created_at', bool $default = true): static
    {
        $default = $default ? "DEFAULT CURRENT_TIMESTAMP" : '';
        $this->setVariables($timestamp, "TIMESTAMP $default");
        return $this;
    }

    /**
     * Делает последнюю созданную переменную уникальной (SQL)
     *
     * @return void
     */
    public function unique(): void
    {
        $variable = array_key_last($this->variables);
        $this->variables[$variable] .= " UNIQUE";
    }

    /**
     * Тип данных JSONB (pgsql)
     *
     * @param string $jsonb
     * @return $this
     */
    public function jsonb(string $jsonb): static
    {
        $this->setVariables($jsonb, "JSONB");
        return $this;
    }

    public function hstore(string $hstore): static
    {
        if (!$this->hstore_status) {
            $db = Application::$app->db;
            $db->pdo->exec("CREATE EXTENSION hstore;");
            $this->hstore_status = true;
        }
        $this->setVariables($hstore, "hstore");

        return $this;
    }

    public function nullable(): static
    {
        $this->setValueForLastVariable(str_replace(" NOT NULL", "", $this->getLastVariableValue()), false);
        return $this;
    }

    public function default(mixed $value): static
    {
        if (is_string($value))
            $this->setValueForLastVariable(" DEFAULT '{$value}'");
        else
            $this->setValueForLastVariable(" DEFAULT $value");

        return $this;
    }

    private function getLastVariableName(): string
    {
        return array_key_last($this->variables);
    }

    private function getLastVariableValue()
    {
        return $this->variables[array_key_last($this->variables)];
    }

    /**
     * Функция для установки значения для последней переменной
     *
     * @param string $value Значение для последней переменной
     * @param bool $complement Дополнить последнюю переменную или полностью ее
     * @return void
     */
    private function setValueForLastVariable(string $value, bool $complement = true): void
    {
        if ($complement)
            $this->variables[array_key_last($this->variables)] .= $value;
        else
            $this->variables[array_key_last($this->variables)] = $value;
    }

    /**
     * Функция, которая выводит получившийся запрос на создание поля в таблице
     * !! ВНИМАНИЕ !!
     * Функция только для стадии разработки
     *
     * @return void
     */
    #[NoReturn] public function check(): void
    {
        dd($this->getLastVariableValue());
    }

}