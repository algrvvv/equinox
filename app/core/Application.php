<?php

namespace Imissher\Equinox\app\core;

use Exception;
use Imissher\Equinox\app\core\database\Database;
use Imissher\Equinox\app\core\exceptions\FailedToOpenStream;
use Imissher\Equinox\app\core\Facades\Container;
use Imissher\Equinox\app\core\Facades\src\Router;
use Imissher\Equinox\app\core\Helpers\MessageLogTrait;
use Imissher\Equinox\app\core\http\Request;
use Imissher\Equinox\app\core\http\Response;
use Imissher\Equinox\app\core\interfaces\Provider;

class Application
{
    use MessageLogTrait;

    /**
     * @var string Рутовый путь
     */
    public static string $ROOT_PATH;

    /**
     * @var string|mixed Версия приложения
     */
    private static string $APP_VERSION;

    /**
     * @var Router|mixed
     */
    public Router $route;

    /**
     * @var Response
     */
    public Response $response;

    /**
     * @var Request
     */
    public Request $request;

    /**
     * @var View
     */
    public View $view;

    /**
     * @var Database
     */
    public Database $db;

    /**
     * @var Master
     */
    public Master $master;

    public static bool $isMaster;

    /**
     * @var Session
     */
    public Session $session;

    /**
     * @var Container
     */
    public Container $container;

    /**
     * @var Application
     */
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
            $this->container = new Container();
            $this->container->set('router', new Router($this->request, $this->response, $this->view, $this->session));
            $this->container->set('log', new Log(config('app', 'log.filename')));
            $this->route = $this->container->get('router');
            $this->db = new Database($config['db']);
            $this->master = new Master($config['master']);
            self::$isMaster = !($config['master'][0] == false);
            $this->bootstrap();
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

    /**
     * Обработка и запуск настроек из всех провайдеров из `app/providers/`
     *
     * @return void
     * @throws FailedToOpenStream
     */
    private function bootstrap(): void
    {
        $providers = config('app', 'providers');
        foreach ($providers as $provider) {
            /** @var Provider $provider */
            $provider->boot();
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
            Log::error($e->getMessage());
        } else {
            $this->messageLog($e->getMessage());
            Log::error($e->getMessage());
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
        $link = ($_SERVER['REQUEST_SCHEME'] ?? $protocol) . "://" . $_SERVER['HTTP_HOST'];
        echo "<link rel='stylesheet' href='" . $link . "/$path'>";
    }

    public static function app_version(): string
    {
        return self::$APP_VERSION;
    }
}
