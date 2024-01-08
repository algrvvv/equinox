<?php

namespace Imissher\Equinox\app\core;

use Imissher\Equinox\app\core\exceptions\ControllerError;
use Imissher\Equinox\app\core\Helpers\RouteTrait;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;

class Controller
{
    use RouteTrait {
        RouteTrait::__construct as private __routeTraitConstruct;
    }

    protected Session $session;

    public function __construct()
    {
        $this->session = Application::$app->session;
        $this->__routeTraitConstruct();
    }

    /**
     * @return array
     * @throws ReflectionException
     * @throws ControllerError
     */
    public function getArgs(): array
    {
        $class = $this;
        $methods = get_class_methods($this);
        $user_methods = []; // массив созданных методов
        foreach ($methods as $method) {
            if ($method === '__construct') break;
            $user_methods[] = $method;
        }

        $args = [];
        foreach ($user_methods as $user_method) {
            $args[$user_method] = $this->get_func_argNames($class, $user_method);
        }

        return $args;
    }

    /**
     * @param $class
     * @param $func
     * @return array
     * @throws ControllerError
     * @throws ReflectionException
     */
    private function get_func_argNames($class, $func): array
    {
        $args = [];
        $f = new ReflectionClass($class);

        if ($f->hasMethod($func)) {
            $ref = new ReflectionMethod ($class, $func);
            $params = $ref->getParameters();
            foreach ($params as $param) {
                $args[] = $param->getName();
            }
            return $args;
        } else {
            throw new ControllerError();
        }
    }

    protected function json(array $json): false|string
    {
        header("Content-Type: application/json");
        return json_encode($json);
    }
}