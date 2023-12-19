<?php

namespace Imissher\Equinox\app\core;

use Imissher\Equinox\app\core\http\Request;
use Imissher\Equinox\app\core\http\Response;
use Imissher\Equinox\app\core\http\Route;

class Application
{
    public static string $ROOT_PATH;
    public Route $route;
    public Response $response;
    public Request $request;
    public View $view;
    public static Application $app;

    public function __construct(string $rootPath)
    {
        self::$ROOT_PATH = $rootPath; // Выбор директории веб приложения
        self::$app = $this;
        $this->request = new Request(); // Создания экземпляра класса Request
        $this->response = new Response(); // Создания экземпляра класса Response
        $this->view = new View();
        $this->route = new Route($this->request, $this->response, $this->view); // Создания экземпляра класса Route
    }

    /**
     * Запускает приложение
     *
     * @return void
     */
    public function run(): void
    {
        echo "Приложение работает <br>";
        echo $this->route->resolve();
    }
}