<?php

namespace Imissher\Equinox\app\core\http;

use Imissher\Equinox\app\core\exceptions\NotFoundException;
use Imissher\Equinox\app\core\Session;
use Imissher\Equinox\app\core\View;


class Route
{
    /**
     * @var string|array|null
     */
    private string|array|null $current_url = null;
    /**
     * @var string|null
     */
    private ?string $current_method = null;
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
     * @var View
     */
    public View $view;
    /**
     * @var Session
     */
    public Session $session;

    /**
     * @var Kernel
     */
    protected Kernel $middlewareKernel;

    /**
     * @param Request $request
     * @param Response $response
     * @param View $view
     * @param Session $session
     */
    public function __construct(Request $request, Response $response, View $view, Session $session)
    {
        $this->middlewareKernel = new Kernel();
        $this->request = $request;
        $this->response = $response;
        $this->view = $view;
        $this->session = $session;
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
        $this->current_url = $route;
        $this->current_method = 'get';
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
        $this->current_url = $route;
        $this->current_method = 'post';
        $this->routes['post'][$route] = $callback;
        return $this;
    }

    /**
     * @throws NotFoundException
     */
    public function resolve()
    {
        $this->middlewareKernel->start();

        $method = $this->request->method();
        $url = $this->request->getUrl();
        $callback = $this->routes[$method][$url] ?? false;

        if (!$callback) {
            throw new NotFoundException();
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
        if ($content = $this->view->check_template($view)->execute())
            return str_replace("{{ content }}", $content, $layoutContent);
        else
            return str_replace("{{ content }}", $viewContent, $layoutContent);

    }

    /**
     * @param string $url
     * @return $this
     */
    public function redirect(string $url): static
    {
        header("Location: $url");
        return $this;
    }

    /**
     * @param string $sub
     * @param string $message
     * @return void
     */
    public function with(string $sub, string $message): void
    {
        $this->session->setFlash($sub, $message);
    }


    /**
     * @param string|array $rule
     * @return false|$this
     */
    public function middleware(string|array $rule): false|static
    {
        if (is_null($this->current_url) || is_null($this->current_method)) return false;
        $this->middlewareKernel->handler($rule, $this->current_url, $this->current_method);
        $this->current_url = null;
        $this->current_method = null;
        return $this;
    }

}
