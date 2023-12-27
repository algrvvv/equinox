<?php

namespace Imissher\Equinox\app\controllers;

use Imissher\Equinox\app\core\Controller;
use Imissher\Equinox\app\core\http\Request;
use Imissher\Equinox\app\models\User;

class RegisterController extends Controller
{
    public function index(): false|array|string
    {
        return $this->render('pages/register');
    }

    public function store(Request $request)
    {
        if ($request->method() == 'post') {
            $user = new User();

            $userData = $user->getData($request->getBody());
            if ($user->validate()) {
                $attrs = [
                    'login' => $userData['login'],
                    'email' => $userData['email'],
                    'password' => password_hash($userData['password'], PASSWORD_BCRYPT),
                ];

                if($user->insert($attrs)){
                    $this->redirect('/register')->with('success', 'Пользователь добавлен');
                }

            } else {
                $this->redirect('/register')->with('error', $user->getFirstError());
            }

        } else {
            return $this->render('pages/register');
        }

    }
}