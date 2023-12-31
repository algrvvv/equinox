<?php

namespace Imissher\Equinox\app\core;

class View
{
    private array $temps = [];
    private string $viewContent;

    /**
     * @param string $view
     * @return $this
     */
    public function check_template(string $view): static
    {
        $viewTemplate = Application::$ROOT_PATH . "/views/$view.view.php";
        $lines = file($viewTemplate);
        foreach ($lines as $line) {
            if (str_contains($line, '@include')) {
                $this->temps['inc'][] = explode(' ', $line);
            }
        }

        return $this;
    }


    /**
     * @return array|string
     */
    private function view_include(): array|string
    {
        $templates = [];

        foreach ($this->temps['inc'] as $inc) {
            if (count($inc) > 1) {
                foreach ($inc as $i){
                    if (str_contains($i, '@include')) {
                        $layout = trim(str_replace("')", '', substr($i, 10)));
                        $templates[] = $layout;
                    }
                }

            } else {
                $layout = trim(str_replace("')", '', substr($inc[0], 10)));
                $templates[] = $layout;
            }
        }

        foreach ($templates as $template){
            $filename = Application::$ROOT_PATH . "/views/$template.view.php";
            if(file_exists($filename)){
                ob_start();
                include_once $filename;
                $this->viewContent = str_replace("@include('$template')", ob_get_clean(), $this->viewContent);
            } else {
                $this->viewContent = str_replace("@include('$template')", "ОШИБКА ИМПОРТА ШАБЛОНА '$template'", $this->viewContent);
            }
        }

        return $this->viewContent;

    }

    /**
     * @return array|false|string
     */
    public function execute(): array|false|string
    {
        if (isset($this->temps['inc'])) {
            return $this->view_include();
        }

        return false;
    }

    /**
     * Получение контента из view
     *
     * @param string $view
     * @param array $params
     * @return false|string
     */
    public function viewContent(string $view, array $params): false|string
    {
        /**
         * |--------------------------------------------------------------------------
         * | Принимается только файлы расширения `.view.php`
         * |--------------------------------------------------------------------------
         */
        foreach ($params as $key => $value){
            $$key = $value;
        }

        ob_start();
        include_once Application::$ROOT_PATH . "/views/$view.view.php";
        $this->viewContent = ob_get_clean();
        return $this->viewContent;
    }

    /**
     * @param string $layout
     * @return false|string
     */
    public function layoutContent(string $layout): false|string
    {
        ob_start();
        include_once Application::$ROOT_PATH . "/views/$layout.view.php";
        return ob_get_clean();
    }

}
