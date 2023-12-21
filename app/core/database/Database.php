<?php

namespace Imissher\Equinox\app\core\database;

use Imissher\Equinox\app\core\Application;

class Database
{
    public \PDO $pdo;

    public Migration $migrate;

    public function __construct(array $db_config)
    {
        $this->migrate = new Migration();
        $dsn = $db_config['dsn'] ?? '';
        $user = $db_config['user'] ?? '';
        $password = $db_config['password'] ?? '';
        $this->pdo = new \PDO($dsn, $user, $password);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
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
                $instance->up();
                $new_migrations[] = $migration;
            }

            if ($this->addNewMigration($new_migrations)) {
                $this->messageLog("Перенос всех таблиц успешно завершен");
            }
        } else {
            $this->messageLog("Нечего переносить");
        }
    }

    /**
     * Создание таблицы миграций
     *
     * @return void
     */
    private function createMigrationTable(): void
    {
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS migrations (
                id INT AUTO_INCREMENT PRIMARY KEY,
                migration VARCHAR(255),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=INNODB;
        ");
    }

    /**
     * Возвращает массив уже созданных миграций
     *
     * @return false|array
     */
    private function appliedMigrations(): false|array
    {
        $statement = $this->pdo->prepare("SELECT `migration` FROM `migrations`");
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_COLUMN);
    }

    private function addNewMigration(array $migrations): bool
    {
        if (!empty($migrations)) {
            foreach ($migrations as $migration) {
                $statement = $this->pdo->prepare("INSERT INTO `migrations` (migration) VALUES ('$migration')");
                return $statement->execute();
            }
        }

        return true;
    }

    private function messageLog(string $message): void
    {
        echo "[" . date('Y-m-d H:i:s') . "] - " . $message . PHP_EOL;
    }
}