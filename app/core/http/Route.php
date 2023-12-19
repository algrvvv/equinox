<?php

namespace Imissher\Equinox\app\core\http;

use Imissher\Equinox\app\core\Application;
use Imissher\Equinox\app\core\View;

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
    public View $view;

    /**
     * @param Request $request
     * @param Response $response
     * @param View $view
     */
    public function __construct(Request $request, Response $response, View $view)
    {
        $this->request = $request;
        $this->response = $response;
        $this->view = $view;
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
             * |--------------------------------------------------------------------------
             * | Рендер нужного шаблона, если указывается шаблон,
             * | а не колл бек или контроллер
             * |--------------------------------------------------------------------------
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

        return call_user_func($callback, $this->request);
    }

    /**
     * Рендер шаблонов
     *
     * @param string $view
     * @param array $params
     * @return array|false|string
     */
    public function render(string $view, array $params = []): array|false|string
    {
        $layoutContent = $this->view->layoutContent('layouts/template');
        $viewContent = $this->view->viewContent($view, $params);
        if($content = $this->view->check_template($view)->execute())
            return str_replace("{{ content }}", $content, $layoutContent);
        else
            return str_replace("{{ content }}", $viewContent, $layoutContent);

    }

}