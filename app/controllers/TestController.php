<?php

namespace Imissher\Equinox\app\controllers;

use Imissher\Equinox\app\core\Controller;

class TestController extends Controller
{
    /**
     * Обычный вариант контроллера
     *
     * @return false|array|string
     */
    public function test(): false|array|string
    {
        return $this->render('test', ["data" => "hello from params"]);
    }

}