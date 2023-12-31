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

            //получение данных с формы
            $userData = $user->getData($request->getBody());
            //валидация по правилам, которые прописаны в модели
            if ($user->validate()) {
                //если все правильно, то формируем массив с данными, которые будем записывать в бд
                $attrs = [
                    'login' => $userData['login'],
                    'email' => $userData['email'],
                    'password' => password_hash($userData['password'], PASSWORD_BCRYPT),
                ];

                //если данные добавляются успешно -> редирект на страницу входа
                if($user->insert($attrs)){
                    $this->redirect('/login')->with('success', 'Пользователь зарегистрирован');
                }

            } else {
                $this->redirect('/register')->with('error', $user->getFirstError());
            }

        } else {
            return $this->render('pages/register');
        }

    }
}