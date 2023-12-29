<?php

namespace Imissher\Equinox\app\core;

use Imissher\Equinox\app\core\exceptions\UndefinedMethod;
use Imissher\Equinox\app\core\Helpers\MessageLogTrait;

class Master
{
    use MessageLogTrait;

    private Application $app;
    private string $table = '$this->table';

    public function __construct(array $config)
    {
        if (count($config) == 1 || $config[0] === false) return;

        $this->app = Application::$app;
        foreach ($config as $item) {
            if ($item == 'master.php') continue;

            if (str_contains($item, ':')) {
                try {
                    $command = explode(':', $config[1]);
                    if (isset($config[2])) {
                        if(method_exists($this, $command[0])){
                            $this->{$command[0]}($command[1], $config[2]);
                        } else {
                            throw new UndefinedMethod();
                        }
                    } else {
                        $this->messageLog("Ошибка: Пропущен обязательный параметр");
                    }
                } catch (\Exception $e) {
                    echo $e->getMessage();
                }
                break;
            } elseif ($item === '-h' || $item === '--help') {
                $this->helpMessage();
            } elseif ($item === 'migrate') {
                $this->migrate();
            } else {
                $this->messageLog("Используйте команду `php master.php --help` для того, чтобы узнать больше");
            }


        }

    }

    /**
     * @throws UndefinedMethod|exceptions\MigrationError
     */
    private function drop(string $type, string $name)
    {
        if($type === 'table'){
            Application::$app->db->downMigration($name);
        } else {
            throw new UndefinedMethod();
        }
    }

    private function create(string $type, string $name): void
    {
        $filename = 'm' . date('His_ymd', time()) . "_" . $name;
        switch ($type) {
            case 'controller':
                file_put_contents(Application::$ROOT_PATH . "/app/controllers/$name.php", "<?php
namespace Imissher\Equinox\app\controllers;
                
use Imissher\Equinox\app\core\Controller;
                
class $name extends Controller
{
}");
                $this->messageLog(ucfirst($type) . " `$name` успешно создан");
                break;
            case 'migration':
                $text = "<?php
namespace Imissher\Equinox\app\database\migrations;

use Imissher\Equinox\app\core\database\Migration;

class $filename extends Migration
{
    public function up(): bool|string
    {
        return $this->table->create('$name', function () {
            $this->table->id();
            $this->table->string('email')->unique();
            $this->table->string('password');
            $this->table->timestamps();
        });
    }

    public function drop(): false|int
    {
        return $this->table->dropTable('$name');
    }
}";
                file_put_contents(Application::$ROOT_PATH . "/app/database/migrations/$filename.php", "$text");
                $this->messageLog(ucfirst($type) . " `$name` успешно создана");
                break;
            default:
                $this->messageLog("Невозможно создать $type");
                break;
        }

//
    }

    private function migrate(): void
    {
        $this->app->db->applyMigration();
    }

    private function helpMessage(): void
    {
        echo "Небольшой мануал по использованию `master.php`:\n
        `migrate` - для переноса всех таблиц бд\n
        `create:controller nameController` - для создания контроллера\n
        `create:migration nameMigration` - для создания миграции\n
        `drop:table nameTable` - для удаление миграции\n
        
        Пример использования:
        php master.php create:controller TestController // создание контроллера с названием TestController
        ";
    }

}