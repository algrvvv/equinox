<?php

namespace Imissher\Equinox\app\core\http;

use Imissher\Equinox\app\core\Application;

class Route
{
    /**
     * @var array
     */
    protected array $routes = [];

    /**
     * @var Request
     */
    public Request $request;
    /**
     * @var Response
     */
    public Response $response;

    /**
     * @param Request $request
     * @param Response $response
     */
    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * Добавление GET запросов
     *
     * @param string $route
     * @param mixed $callback
     * @return $this
     */
    public function get(string $route, mixed $callback): static
    {
        $this->routes['get'][$route] = $callback;
        return $this;
    }

    /**
     * Добавление POST запросов
     *
     * @param string $route
     * @param mixed $callback
     * @return $this
     */
    public function post(string $route, mixed $callback): static
    {
        $this->routes['post'][$route] = $callback;
        return $this;
    }

    public function resolve()
    {
        $method = $this->request->method();
        $url = $this->request->getUrl();
        $callback = $this->routes[$method][$url] ?? false;

        if (!$callback) {
            $this->response->setResponseCode(404);
            //TODO переадресация на _404
            return "404";
        }

        if (is_string($callback)) {
            /**
             |--------------------------------------------------------------------------
             | Рендер нужного шаблона, если указывается шаблон,
             | а не колл бек или контроллер
             |--------------------------------------------------------------------------
             */
            return $this->render($callback);
        }

        if (is_array($callback)) {
            /**
             * |--------------------------------------------------------------------------
             * | Здесь также должно быть (возможно, пока оно нам не нужно)
             * | присвоение переменной Controller $controller нужного коллбека
             * | но пока этого нет. //TODO
             * |--------------------------------------------------------------------------
             * | array $callback
             * | [0] -> class Controller
             * | [1] -> Controller's method
             * |--------------------------------------------------------------------------
             */
            $callback[0] = new $callback[0]();
        }

        return call_user_func($callback);
    }

    /**
     * @param string $view
     * @param array $params
     * @return 'content'
     */
    public function render(string $view, array $params = [])
    {
        return $layoutContent = 'get layout content';
        $viewContent = $this->viewContent($view);
        //return str_replace("{{content}}", $viewContent, $layoutContent);
    }

    /**
     * @return void
     */
    protected function layoutContent()
    {

    }

    /**
     * Получение контента из view
     *
     * @param string $view
     * @return false|string
     */
    protected function viewContent(string $view): false|string
    {
        /**
        |--------------------------------------------------------------------------
        | Принимается только файлы расширения `.view.php`
        |--------------------------------------------------------------------------
        */
        ob_start();
        include_once Application::$ROOT_PATH . "/views/$view.view.php";;
        return ob_get_clean();
    }
}