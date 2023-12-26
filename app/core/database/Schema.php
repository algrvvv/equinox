<?php

namespace Imissher\Equinox\app\core\database;

use Exception;
use Imissher\Equinox\app\core\Application;

class Schema
{
    private string $query;
    private array $variables = [];

    /**
     * Создание миграции
     *
     * @param string $db_name
     * @param $callback
     * @return bool|string Возвращает `false` в случае ошибки. Иначе возвращает `db_name`
     */
    public function create(string $db_name, $callback): bool|string
    {
        $this->messageLog("Начало переноса `$db_name`");
        $this->query = "CREATE TABLE `$db_name`";
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

    private function query_exec(): false|int
    {
        $structure = "";
        foreach ($this->variables as $key => $value) {
            $structure .= "`$key` $value, ";
        }
        $query = $this->query . " ( " . trim($structure, ', ') . ") ENGINE=INNODB";
        $this->query = $query;
        $db = Application::$app->db;
        return $db->pdo->exec($this->query);
    }

    /**
     * Удаление таблицы
     *
     * @param string $table
     * @return false|int
     */
    public function dropTable(string $table): false|int
    {
        $this->query = "DROP TABLE `$table`";
        $db = Application::$app->db;
        return $db->pdo->exec($this->query);
    }

    /**
     * @param string $name
     * @param string $structure
     * @return void
     */
    private function setVariables(string $name, string $structure): void
    {
        $this->variables[$name] = $structure;
    }

    /**
     * Создание переменной `id`
     *
     * @param string $id
     * @return $this
     */
    public function id(string $id = 'id'): static
    {
        $this->setVariables($id, "INT AUTO_INCREMENT PRIMARY KEY");
        return $this;
    }

    /**
     * Создание переменной типа `string`
     *
     * @param string $string Название колонки
     * @param int $length Максимальная допустимая длина
     * @param bool $not_null Разрешить / Запретить значение NULL
     * @return $this
     */
    public function string(string $string, int $length = 255, bool $not_null = true): static
    {
        $not_null = $not_null ? 'NOT NULL' : '';
        $this->setVariables($string, "VARCHAR($length) $not_null");
        return $this;
    }

    /**
     * Создание переменной типа `timestamp`
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
     * Делает последнюю созданную переменную уникальной
     *
     * @return void
     */
    public function unique(): void
    {
        $variable = array_key_last($this->variables);
        $this->variables[$variable] .= " UNIQUE";
    }

    private function messageLog(string $message): void
    {
        echo "[" . date('Y-m-d H:i:s') . "] - " . $message . PHP_EOL;
    }
}