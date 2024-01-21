<?php

namespace Imissher\Equinox\app\core;

use Exception;
use Imissher\Equinox\app\core\exceptions\UndefinedMethod;
use Imissher\Equinox\app\core\Helpers\MessageLogTrait;

class Master
{
    use MessageLogTrait;

    private Application $app;
    private string $table = '$this->table';

    public function __construct(array $config)
    {
        //TODO create:model / create:migration -m
        if (count($config) == 1 || $config[0] === false) return;

        $this->app = Application::$app;
        $app_version = $this->app::app_version();
        $php_version = phpversion();
        foreach ($config as $item) {
            if ($item == 'master') continue;

            if (str_contains($item, ':')) {
                try {
                    $command = explode(':', $config[1]);
                    if (isset($config[2])) {
                        $options = null;
                        if(isset($config[3])) $options = $config[3];
                        if(method_exists($this, $command[0])){
                            $this->{$command[0]}($command[1], $config[2], $options);
                        } else {
                            throw new UndefinedMethod();
                        }
                    } else {
                        $this->messageLog("Ошибка: Пропущен обязательный параметр");
                    }
                } catch (Exception $e) {
                    echo $e->getMessage();
                }
                break;
            } elseif ($item === '-h' || $item === '--help') {
                $this->helpMessage();
            } elseif ($item === "serve") {
                $this->messageLog("\033[0;32mЗапуск сервера. Не забудьте подключить базу данных!\033[0m");
                $cd = shell_exec("cd public && php -S localhost:8080");
            } elseif ($item === '-v' || $item === '--version'){
                $this->messageLog("PHP Version: \033[0;32m$php_version\033[0m");
                $this->messageLog("App Version: \033[0;32m$app_version\033[0m");
            } elseif ($item === 'migrate') {
                $this->migrate();
            } else {
                $this->messageLog("Используйте команду `php master --help` для того, чтобы узнать больше");
            }


        }

    }

    /**
     * @throws UndefinedMethod|exceptions\MigrationError
     */
    private function drop(string $type, string $name, mixed $options): void
    {
        if($type === 'table'){
            Application::$app->db->downMigration($name);
        } elseif ($type === 'tables') {
            if($name != "true") return;
            Application::$app->db->downMigrations();
        }
        else {
            throw new UndefinedMethod();
        }
    }

    private array $options = [
        "-m"
    ];
    private function create(string $type, string $name, ?string $options): void
    {
        if(!is_null($options)){
            if(!in_array($options, $this->options)){
                $this->messageLog("Ошибка! Недопустимый флаг");
                exit;
            }
        }


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

                if($options === "-m") {
                    $this->createModel($name);
                    $this->messageLog(ucfirst($type) . " `$name` с моделью успешно созданы");
                    break;
                }
                $this->messageLog(ucfirst($type) . " `$name` успешно создана");
                break;
            case 'model':
                $this->createModel($name);
                $this->messageLog(ucfirst($type) . " `$name` успешно создана");
                break;
            default:
                $this->messageLog("Невозможно создать $type");
                break;
        }
    }

    private function migrate(): void
    {
        $this->app->db->applyMigration();
    }

    private function helpMessage(): void
    {
        echo "Небольшой мануал по использованию `master`:\n
\033[0;32m migrate \033[0m - для переноса всех таблиц бд\n
\033[0;32m create:controller nameController \033[0m - для создания контроллера\n
\033[0;32m create:migration nameMigration \033[0m - для создания миграции\n
\033[0;32m create:migration nameMigration -m \033[0m - для создания миграции вместе с моделью\n
\033[0;32m create:model nameModel \033[0m - для создания модели\n
\033[0;32m drop:table nameTable \033[0m - для удаление миграции\n
\033[0;32m drop:tables true \033[0m - для удаление всех миграций\n

\033[0;31m Пример использования \033[0m:
 php master create:controller ProfileController // создание контроллера с названием ProfileController
";
    }

    private function createModel(string $name): void
    {
        $modelName = ucfirst($name);
        $modeltext = "<?php

namespace Imissher\Equinox\app\models;

use Imissher\Equinox\app\core\database\DbModel;

class $modelName extends DbModel
{

// TODO Add the required fields

    protected function rules(): array
    {
        // TODO: Implement rules() method.
    }

    protected function tableName(): string
    {
        return '$name';
    }
}
";
        file_put_contents(Application::$ROOT_PATH . "/app/models/$modelName.php", "$modeltext");
    }

}