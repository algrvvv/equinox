<?php

namespace Imissher\Equinox\app\core\database;

use Exception;
use Imissher\Equinox\app\core\Application;
use Imissher\Equinox\app\core\exceptions\ConnectionError;
use Imissher\Equinox\app\core\exceptions\MigrationError;
use Imissher\Equinox\app\core\Helpers\MessageLogTrait;
use PDO;

class Database
{
    use MessageLogTrait;

    public PDO $pdo;

    public Migration $migrate;

    public string $db_driver = 'mysql';
    public string $db_name;

    private array $support_drivers = [
        "mysql", "pgsql"
    ];

    /**
     * @throws ConnectionError
     */
    public function __construct(array $db_config)
    {
        $driver = $db_config['driver'];
        if (!in_array($driver, $this->support_drivers)) throw new ConnectionError();

        $this->migrate = new Migration();
        $this->db_driver = $driver;
        $dsn = $db_config['driver'] . ":" . $db_config['dsn'];
        $this->db_name = str_replace('dbname=', '', explode(';', $dsn)[2]);

        $user = $db_config['user'] ?? '';
        $password = $db_config['password'] ?? '';
        try {
            $this->pdo = new PDO($dsn, $user, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (Exception $e){
            throw new ConnectionError();
        }
    }

    public function applyMigration(): void
    {
        $this->createMigrationTable();
        $appliedMigrations = $this->appliedMigrations();
        $allMigrations = array_diff(scandir(Application::$ROOT_PATH . "/app/database/migrations"), [".", ".."]);
        $migrations = array_diff($allMigrations, $appliedMigrations);

        $new_migrations = [];

        if (!empty($migrations)) {
            foreach ($migrations as $migration) {
                $pathToClass = Application::$ROOT_PATH . "/app/database/migrations/$migration";
                require_once $pathToClass;
                $className = "Imissher\\Equinox\\app\\database\\migrations\\" . pathinfo("$migration", PATHINFO_FILENAME);
                $instance = new $className();
                $db_name = $instance->up();
                $new_migrations[$db_name] = $migration;
            }

            if ($this->addNewMigration($new_migrations)) {
                $this->messageLog("Перенос всех таблиц успешно завершен");
            }
        } else {
            $this->messageLog("Нечего переносить");
        }
    }

    /**
     * @throws MigrationError
     */
    public function downMigration(string $table): void
    {
        $this->createMigrationTable();
        $migration = $this->findTable($table);
        if (gettype($migration) === "boolean") {
            throw new MigrationError();
        } else {
            $migration = $migration['migration'];
        }
        if ($this->deleteMigration($table)) {
            $className = "Imissher\\Equinox\\app\\database\\migrations\\" . pathinfo("$migration", PATHINFO_FILENAME);
            $instance = new $className();
            if ($instance->drop() !== false) {
                $this->messageLog("Удаление завершено");
            }

        } else {
            throw new MigrationError();
        }

    }

    /**
     * Удаление всех таблиц
     *
     * @throws MigrationError
     */
    public function downMigrations(): void
    {
        $driver = $this->db_driver;
        if ($driver === "pgsql") {
            $schema = "SELECT CURRENT_SCHEMA;";
            $statement = $this->pdo->prepare($schema);
            $statement->execute();
            $res = $statement->fetchAll(PDO::FETCH_COLUMN)[0];
            try {
                $this->pdo->exec("drop schema $res cascade;");
                $this->pdo->exec("create schema $res;");
            } catch (Exception $e) {
                throw new MigrationError();
            }
        } elseif ($driver === "mysql") {
            $dbname = $this->db_name;
            try {
                $this->pdo->exec("TRUNCATE TABLE $dbname;");
            } catch (Exception $e) {
                throw new MigrationError();
            }
        }
    }

    private function findTable(string $table)
    {
        $statement = $this->pdo->prepare("SELECT migration FROM migrations WHERE dbname = '$table'");
        $statement->execute();
        return $statement->fetch();
    }

    private function deleteMigration(string $table): bool
    {
        $statement = $this->pdo->prepare("DELETE FROM migrations WHERE dbname = '$table'");
        return $statement->execute();
    }

    /**
     * Создание таблицы миграций
     *
     * @return void
     */
    private function createMigrationTable(): void
    {
        $conf = require_once "./app/core/config/database.php";
        $migration_table = $conf['connections'][$this->db_driver]['migration_table'];
        $this->pdo->exec($migration_table);
    }

    /**
     * Возвращает массив уже созданных миграций
     *
     * @return false|array
     */
    private function appliedMigrations(): false|array
    {
        $statement = $this->pdo->prepare("SELECT migration FROM migrations");
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_COLUMN);
    }

    private function addNewMigration(array $migrations): bool
    {
        if (!empty($migrations)) {
            foreach ($migrations as $db => $migration) {
                $statement = $this->pdo->prepare("INSERT INTO migrations (migration, dbname) VALUES ('$migration', '$db')");
                return $statement->execute();
            }
        }

        return true;
    }

}