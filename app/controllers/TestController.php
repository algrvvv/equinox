<?php

namespace Imissher\Equinox\app\controllers;

use Imissher\Equinox\app\core\Controller;
use Imissher\Equinox\app\core\exceptions\ControllerError;
use Imissher\Equinox\app\core\http\Request;

class TestController extends Controller
{
    public function index(mixed $id, string $name): false|array|string
    {
        return $this->render('pages/test', [
            "id" => $id, "name" => $name
        ]);
    }

}
