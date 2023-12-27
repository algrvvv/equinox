<?php

namespace Imissher\Equinox\app\core\database;

use Imissher\Equinox\app\core\Application;
use Imissher\Equinox\app\core\exceptions\ReceivingData;
use Imissher\Equinox\app\core\Model;

abstract class DbModel extends Model
{
    private string $query = '';
    private array $bindValues = [];
    private object $db;
    private string $table = '';

    public function __construct()
    {
        $this->db = Application::$app->db->pdo;
        $this->table = $this->tableName();
    }

    abstract protected function tableName(): string;

    public function insert(array $attrs)
    {
        $keys = array_keys($attrs);
        $params = array_map(fn($k) => ":$k", $keys);
        $sql = "INSERT INTO `$this->table` (" . implode(', ', $keys) . ") VALUES (" . implode(', ', $params) . ")";
        $statement = $this->db->prepare($sql); // подготовка запроса
        foreach ($keys as $key) {
            $statement->bindValue(":$key", $attrs[$key]);
        }

        return $statement->execute();
    }

    public function update(array $values, array $condition)
    {
        $sql = "UPDATE `$this->table` SET ";
        $keys = array_keys($values);
        $attrs = array_map(fn($attr) => ":$attr", $keys);

        $cond_amount = 1;
        $value_amount = 1;
        foreach ($keys as $key => $value) {
            if ($value_amount > 1) {
                $sql .= ", `$value` = $attrs[$key]";
            } else {
                $sql .= "`$value` = $attrs[$key]";
            }
            $value_amount++;
        }

        foreach ($condition as $key => $value) {
            if ($cond_amount > 1) {
                $sql .= " AND `$key` = :$key";
            } else {
                $sql .= " WHERE `$key` = :$key";
            }
            $cond_amount++;
        }

        $statement = $this->db->prepare(trim($sql));

        /** Привязка значений данных для обновления */
        foreach ($values as $key => $value) {
            $statement->bindValue(":$key", $value);
        }

        /** Привязка значений условий для обновления */
        foreach ($condition as $key => $value) {
            $statement->bindValue(":$key", $value);
        }

        return $statement->execute();
    }

    public function delete(array $condition)
    {
        $sql = "DELETE FROM `$this->table` WHERE";
        $cond_amount = 1;
        foreach ($condition as $key => $value) {
            if ($cond_amount > 1) {
                $sql .= " AND `$key` = :$key";
            } else {
                $sql .= "`$key` = :$key";
            }
            $cond_amount++;
        }

        $statement = $this->db->prepare(trim($sql));

        foreach ($condition as $key => $value) {
            $statement->bindValue(":$key", $value);
        }

        return $statement->execute();
    }

    /**
     * @throws ReceivingData
     */
    public function select(mixed $select): static
    {
        $sql = "SELECT ";
        if (is_string($select))
            $sql .= $select;
        elseif (is_array($select))
            $sql .= implode(', ', $select);
        else
            throw new ReceivingData();

        $this->query = $sql . " FROM `$this->table`";

        return $this;
    }

    /**
     * @throws ReceivingData
     */
    public function where(array $fields): static
    {
        if(empty($fields)) throw new ReceivingData();

        $sql = $this->query;
        if(strlen($this->query) < 1) $sql = "SELECT * FROM `$this->table`";

        foreach ($fields as $col => $val){
            if (str_contains($sql, 'WHERE')) {
                    $sql .= " AND $col = :$col";
            } else {
                    $sql .= " WHERE $col = :$col";
            }

            $this->bindValues[$col] = $val;
        }

        $this->query = $sql;
        return $this;
    }

    public function get()
    {
        $statement = $this->db->prepare(trim($this->query));

        if(count($this->bindValues) > 0){
            foreach ($this->bindValues as $key => $value) {
                $statement->bindValue(":$key", $value);
            }
        }

        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function toSql(): string
    {
        return $this->query;
    }

    private function getErrorFromSql(string $messageSql)
    {
        $sql = explode(' ', $messageSql);
        $code = $sql[0];
        $dublicateKey = $sql[count($sql) - 1] ?? false;

        return match ($code) {
            'SQLSTATE[23000]:' => "привет",
            default => "Произошла ошибка во время работы с базой данных"
        };
    }
}