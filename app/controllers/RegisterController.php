<?php

namespace Imissher\Equinox\app\controllers;

use Imissher\Equinox\app\core\Controller;
use Imissher\Equinox\app\core\http\Request;

class RegisterController extends Controller
{
    public function index()
    {
        return $this->render('pages/register');
    }

    public function store(Request $request)
    {
        echo "<pre>";
        print_r($request->getBody());
        echo "</pre>";
        exit;
    }
}