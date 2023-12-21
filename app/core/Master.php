<?php

namespace Imissher\Equinox\app\core;

class Master
{
    private Application $app;
    private string $table = '$this->table';

    public function __construct(array $config)
    {
        if(count($config) == 1 && $config[0] === false) return;
        $this->app = Application::$app;
        foreach ($config as $item) {
            if ($item == 'master.php') continue;

            if (str_contains($item, ':')) {
                try {
                    $command = explode(':', $config[1]);
                    if(isset($config[2])){
                        $this->{$command[0]}($command[1], $config[2]);
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
                echo "Используйте команду `php master.php --help` для того, чтобы узнать больше";
            }


        }

    }

    private function create(string $type, string $name): void
    {
        $filename = date('H_i_s_ymd', time()) . "_" . $name;
        switch ($type){
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

class $name extends Migration
{
    public function up(): void
    {
        $this->table->create('$name', function () {
            $this->table->id();
            $this->table->string('email')->unique();
            $this->table->string('password');
            $this->table->timestamps();
        });
    }

    public function down()
    {
        $this->table->dropTable('$name');
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
        
        Пример использования:
        php master.php create:controller TestController // создание контроллера с названием TestController
        ";
    }

    private function messageLog(string $message): void
    {
        echo "[" . date('Y-m-d H:i:s') . "] - " . $message . PHP_EOL;
    }

}