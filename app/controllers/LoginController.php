<?php
namespace Imissher\Equinox\app\controllers;
                
use Imissher\Equinox\app\core\Controller;
use Imissher\Equinox\app\core\exceptions\ReceivingData;
use Imissher\Equinox\app\core\http\Request;
use Imissher\Equinox\app\models\User;

class LoginController extends Controller
{
    public function index(): false|array|string
    {
        return $this->render('pages/login');
    }

    /**
     * @throws ReceivingData
     */
    public function login(Request $request)
    {
        if ($request->isPost()){
            $user = new User();

            $userData = $request->getBody();
            $data = $user->where(['email' => $userData['email']])->get();

            if(!$data){
                $this->redirect('/login')->with('error', 'Пользователя с такой почтой нет');
            }

            if(!password_verify($userData['password'], $data['password'])){
                $this->redirect('/login')->with('error', 'Неправильный пароль');
            }

            $this->session->set('user', $data['id']);
            $this->redirect('/')->with('success', 'Добро пожаловать');
        } else {
            $this->redirect('/');
        }

    }
}