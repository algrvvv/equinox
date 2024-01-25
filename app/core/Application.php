<?php

namespace Imissher\Equinox\app\core;

use Exception;
use Imissher\Equinox\app\core\database\Database;
use Imissher\Equinox\app\core\Helpers\MessageLogTrait;
use Imissher\Equinox\app\core\http\Request;
use Imissher\Equinox\app\core\http\Response;
use Imissher\Equinox\app\core\http\Route;

class Application
{
    use MessageLogTrait;
    public static string $ROOT_PATH;
    private static string $APP_VERSION;
    public Route $route;
    public Response $response;
    public Request $request;
    public View $view;
    public Database $db;
    public Master $master;
    public Session $session;
    public static Application $app;


    public function __construct(string $rootPath, array $config)
    {
        try {
            $d_error_status = $config['display_error'];
            if ($d_error_status === 'false') error_reporting(0); // false -> отключение отображения ошибок

            self::$ROOT_PATH = $rootPath; // Выбор директории веб приложения
            self::$APP_VERSION = $config['app_version'];
            self::$app = $this;
            $this->request = new Request();
            $this->response = new Response();
            $this->view = new View();
            $this->session = new Session();
            $this->route = new Route($this->request, $this->response, $this->view, $this->session);
            $this->db = new Database($config['db']);
            $this->master = new Master($config['master']);
        } catch (Exception $e) {
            $this->error_handler($e, $config['master'][0]);
        }
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
        } catch (Exception $e) {
            $this->error_handler($e);
        }

    }

    public function error_handler(Exception $e, bool $master = false): void
    {
        if (!$master) {
            if (gettype($e->getCode()) === 'string') {
                $this->response->setResponseCode(500);
            } else {
                $this->response->setResponseCode($e->getCode());
            }
            echo $this->route->render('_error', [
                'exception' => $e
            ]);
        } else {
            $this->messageLog($e->getMessage());
            exit;
        }
    }

    public static function isGuest(): bool
    {
        $status = self::$app->session->get('user');

        return !is_int($status);
    }

    public static function style(string $path): void
    {
        $protocol = isset($_SERVER['HTTPS']) ? "https" : "http";
        $link = ($_SERVER['REQUEST_SCHEME'] ?? $protocol). "://" . $_SERVER['HTTP_HOST'];
        echo "<link rel='stylesheet' href='" . $link . "/$path'>";
    }

    public static function app_version(): string
    {
        return self::$APP_VERSION;
    }
}
