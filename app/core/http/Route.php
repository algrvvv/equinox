<?php

namespace Imissher\Equinox\app\core\http;

use Imissher\Equinox\app\core\exceptions\ControllerError;
use Imissher\Equinox\app\core\exceptions\NotFoundException;
use Imissher\Equinox\app\core\Helpers\DynamicUrlTrait;
use Imissher\Equinox\app\core\Session;
use Imissher\Equinox\app\core\View;


class Route
{
    use DynamicUrlTrait;

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

    protected array $variables = [];

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
        $router = $this->method_helper($route);

        $this->current_url = $router;
        $this->current_method = 'get';
        $this->routes['get'][$router] = $callback;
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
        $router = $this->method_helper($route);

        $this->current_url = $router;
        $this->current_method = 'post';
        $this->routes['post'][$router] = $callback;
        return $this;
    }

    /**
     * Добавление DELETE запросов
     *
     * @param string $route
     * @param mixed $callback
     * @return $this
     */
    public function delete(string $route, mixed $callback): static
    {
        $router = $this->method_helper($route);

        $this->current_url = $router;
        $this->current_method = 'delete';
        $this->routes['delete'][$router] = $callback;
        return $this;
    }

    /**
     * Добавление PUT запросов
     *
     * @param string $route
     * @param mixed $callback
     * @return $this
     */
    public function put(string $route, mixed $callback): static
    {
        $router = $this->method_helper($route);

        $this->current_url = $router;
        $this->current_method = 'put';
        $this->routes['put'][$router] = $callback;
        return $this;
    }

    public function patch(string $route, mixed $callback): static
    {
        $router = $this->method_helper($route);

        $this->current_url = $router;
        $this->current_method = 'patch';
        $this->routes['patch'][$router] = $callback;
        return $this;
    }

    private function method_helper(string $route): string
    {
        $route_parts = $this->get_url($route);
        $router = $route_parts['url'];
        if (!is_null($route_parts['variables'])) {
            $this->variables[$router] = $route_parts['variables'];
        }

        return $router;
    }

    /**
     * @throws NotFoundException
     * @throws ControllerError
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
            return $this->render($callback);
        }

        if (is_array($callback)) {
            $callback[0] = new $callback[0]();
        }

        if (isset($this->variables[$url])) {
            $args = [];
            $input_args = $callback[0]->getArgs()[$callback[1]];
            foreach ($input_args as $input_arg) {
                if ($input_arg === 'request') {
                    $args['request'] = $this->request;
                } elseif ($input_arg === 'req') {
                    $args['req'] = $this->request;
                }
            }
            foreach ($this->variables[$url] as $variable => $value) {
                $args[$variable] = $value;
            }

            return call_user_func_array($callback, $args);
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
