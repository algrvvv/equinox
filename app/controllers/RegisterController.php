<?php

namespace Imissher\Equinox\app\controllers;

use Imissher\Equinox\app\core\Controller;
use Imissher\Equinox\app\core\http\Request;
use Imissher\Equinox\app\models\User;

class RegisterController extends Controller
{
    public function index()
    {
        return $this->render('pages/register');
    }

    public function store(Request $request)
    {
        if ($request->method() == 'post') {
            $user = new User();

            $user->getData($request->getBody());
            $user->validate();

        } else {
            return $this->render('pages/register');
        }

    }
}