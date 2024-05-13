<?php

use Imissher\Equinox\app\core\Application;
use Imissher\Equinox\app\core\exceptions\FailedToOpenStream;
use JetBrains\PhpStorm\NoReturn;


if (!function_exists('redirect')) {
    /**
     * Функция для перенаправления на указанный адрес
     *
     * @param string $url
     * @return void
     */
    function redirect(string $url): void
    {
        $route = Application::$app->route;
        $route->redirect($url);
    }
}

if (!function_exists('render')) {
    /**
     * Рендер нужного шаблона
     *
     * @param string $view Название шаблона
     * @param array $params Параметры, которые будут переданы в шаблон
     * @return false|array|string
     */
    function render(string $view, array $params = []): false|array|string
    {
        $route = Application::$app->route;
        return $route->render($view, $params);
    }
}

if (!function_exists('show')) {
    /**
     * Вывод данных
     *
     * @param mixed $data
     * @return void
     */
    function show(mixed $data): void
    {
        echo "<pre>";
        print_r($data);
        echo "</pre>";
    }
}

if (!function_exists('dd')) {
    /**
     * Вывод данных в консоль и завершение выполнения программы
     *
     * @param mixed $data
     * @return void
     */
    #[NoReturn] function dd(mixed $data): void
    {
        $bt = debug_backtrace();
        $caller = array_shift($bt);
        $file = str_replace(Application::$ROOT_PATH, "", $caller['file']);

        if (Application::$isMaster) {
            echo "\033[0;37mDebug: $file : {$caller['line']}\033[0m\n";
            var_dump($data);
        } else {
            echo "<u>Debug: $file : {$caller['line']}</u><br>";
            echo "<pre>";
            var_dump($data);
            echo "</pre>";
        }
        exit;


    }
}

if (!function_exists('env')) {
    /**
     * Функция, которая возвращает значение из .env файла по указанному ключу
     *
     * @param string $key Ключ, по которому нужно получить значение
     * @param string $defaultValue Задаваемое значение по умолчанию, если не найден ключ
     * @return string
     */
    function env(string $key, string $defaultValue = ''): string
    {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/../../../");
        $dotenv->load();

        return $_ENV[$key] ?? $defaultValue;
    }
}

if (!function_exists('config')) {
    /**
     * Получение данных из нужного конфигурационного файла
     *
     * @param string $configFile Название файла в папке app/core/config
     * @param string|null $keys Необязательная переменная для получения значений по ключам
     * @return mixed
     * @throws FailedToOpenStream
     */
    function config(string $configFile, string $keys = null): mixed
    {
        $path = Application::$ROOT_PATH . "/app/core/config/$configFile.php";

        if(!file_exists($path)) throw new FailedToOpenStream($configFile);

        $config = require $path;

        if ($keys === null) return $config;

        $arrayOfParams = explode('.', $keys);

        $array = [];
        foreach ($arrayOfParams as $param) {
            if (empty($array))
                $array = $config[$param];
            else
                $array = $array[$param];
        }

        return $array;


    }
}
