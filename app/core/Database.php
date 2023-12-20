<?php

namespace Imissher\Equinox\app\core;

class Database
{
    public \PDO $pdo;

    public function __construct(array $db_config)
    {
        $dsn = $db_config['dsn'] ?? '';
        $user = $db_config['user'] ?? '';
        $password = $db_config['password'] ?? '';
        $this->pdo = new \PDO($dsn, $user, $password);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    public function applyMigration()
    {
        $this->messageLog("Начинается миграция баз данных");
        $this->createMigrationTable();
        $appliedMigrations =$this->appliedMigrations();

        if(!empty($appliedMigrations)){
            //TODO получение миграций
        }

        $this->messageLog("Нечего переносить");
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

    private function messageLog(string $message): void
    {
        echo "[" . date('Y-m-d H:i:s') ."] - " . $message . PHP_EOL;
    }
}