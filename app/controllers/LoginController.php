<?php

namespace Imissher\Equinox\app\controllers;

use Imissher\Equinox\app\core\Controller;
use Imissher\Equinox\app\core\exceptions\ReceivingData;
use Imissher\Equinox\app\core\http\Request;
use Imissher\Equinox\app\models\User;
use JetBrains\PhpStorm\NoReturn;

class LoginController extends Controller
{
    public function index(): false|array|string
    {
        return render('pages/login');
    }

    /**
     * @throws ReceivingData
     */
    public function login(Request $request): void
    {
        if ($request->isPost()) {
            $user = new User();

            //получение данных с формы
            $userData = $user->getData($request->getBody());
            //правила для валидации данных
            $rules = [
                'email' => ['required', 'email'],
                'password' => ['required']
            ];
            //редирект в случае несоблюдения правил
            if (!$user->validate($rules)) {
                $this->redirect('/login')->with('error', $user->getFirstError());
            }

            //получение данных из бд
            $data = $user->where(['email' => $userData['email']])->get();
            //проверка на наличие такого пользователя
            if (!$data) {
                $this->redirect('/login')->with('error', 'Пользователя с такой почтой нет');
            }
            //проверка пароля
            if (!password_verify($userData['password'], $data['password'])) {
                $this->redirect('/login')->with('error', 'Неправильный пароль');
            }

            //запись в сессию и редирект на главную
            $this->session->set('user', $data['id']);
            $this->redirect('/')->with('success', 'Добро пожаловать');
        } else {
            $this->redirect('/');
        }
    }

    #[NoReturn] public function logout(): void
    {
        $uid = $this->session->get('user');

        if (!$uid) $this->redirect('/')->with('error', 'У вас нет доступа к этой странице');


        $this->session->remove('user');
        $this->redirect('/')->with('success', 'Вы успешно вышли из своего аккаунта');
    }
}
