<?php

namespace Imissher\Equinox\app\core;

use Imissher\Equinox\app\core\database\Database;
use Imissher\Equinox\app\core\exceptions\NotFoundException;
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
    public Database $db;
    public Master $master;
    public static Application $app;

    public function __construct(string $rootPath, array $config)
    {
        self::$ROOT_PATH = $rootPath; // Выбор директории веб приложения
        self::$app = $this;
        $this->request = new Request();
        $this->response = new Response();
        $this->db = new Database($config['db']);
        $this->view = new View();
        $this->route = new Route($this->request, $this->response, $this->view);
        $this->master = new Master($config['master']);
    }

    /**
     * Запускает приложение
     *
     * @return void
     */
    public function run(): void
    {
        try {
            echo $this->route->resolve();
        } catch (\Exception $e){
            $this->response->setResponseCode($e->getCode());
            echo $this->route->render('_error', [
                'exception' => $e
            ]);
        }

    }
}