<?php

namespace Imissher\Equinox\app\core\database;

use Imissher\Equinox\app\core\Application;
use Imissher\Equinox\app\core\Model;

abstract class DbModel extends Model
{
    private object $db;

    public function __construct()
    {
        $this->db = Application::$app->db->pdo;
    }

    abstract protected function tableName(): string;

    public function insert(array $attrs)
    {
        $table = $this->tableName();
        $keys = array_keys($attrs);
        $params = array_map(fn($k) => ":$k", $keys);
        $sql = "INSERT INTO `$table` (" . implode(', ', $keys) . ") VALUES (" . implode(', ', $params) . ")";
        $statement = $this->db->prepare($sql); // подготовка запроса
        foreach ($keys as $key) {
            $statement->bindValue(":$key", $attrs[$key]);
        }

        return $statement->execute();
        /*try {
            return $statement->execute();
        } catch (\Exception $e) {
            return $this->getErrorFromSql($e->getMessage());
        }*/
    }

    private function getErrorFromSql(string $messageSql)
    {
        $sql = explode(' ', $messageSql);
        $code = $sql[0];
        $dublicateKey = $sql[count($sql) - 1] ?? false;
        echo "<pre>";
        print_r($dublicateKey);
        echo "</pre>";

        return match ($code){
            'SQLSTATE[23000]:' => "привет",
            default => "Произошла ошибка во время работы с базой данных"
        };
    }
}