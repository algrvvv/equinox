<?php

namespace Imissher\Equinox\app\core\Helpers;

trait DynamicUrlTrait
{
    private function check_dynamic_url(string $url): bool
    {
        return str_contains($url, '{') && str_contains($url, '}');
    }

    public function get_url(string $route): array
    {
        $router = "";
        $out = ["url" => [], "variables" => []];
        $url = $_GET['url']; //TODO разобраться с $this->request->getBody() // $_GET помогает
        if ($this->check_dynamic_url($route)) {
            $dyn_route = array_filter(explode('/', trim($route, '/')));
            $dyn_url = array_filter(explode('/', $url));
            foreach ($dyn_route as $key => $part) {
                if ($this->check_dynamic_url($part)) {
                    if(!isset($dyn_url[$key])){
                        $out['url'] = $route;
                        $out['variables'] = null;
                        return $out;
                    }
                    $variable_name = str_replace('{', '', $part);
                    $variable_name = str_replace('}', '', $variable_name);
                    $variable_value = $dyn_url[$key];
                    $router .= $dyn_url[$key] . "/";
                    $out['variables'][$variable_name] = $variable_value;
                } else {
                    $router .= $part . "/";
                }

                $out['url'] = "/" . trim($router, "/");
            }
        } else {
            $out['url'] = $route;
            $out['variables'] = null;
        }

        return $out;
    }
}
