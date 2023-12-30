<?php

namespace Imissher\Equinox\app\controllers;

use Imissher\Equinox\app\core\Controller;
use Imissher\Equinox\app\core\exceptions\ReceivingData;
use Imissher\Equinox\app\models\User;

class ProfileController extends Controller
{
    /**
     * @throws ReceivingData
     */
    public function profile(): false|array|string
    {
        $user = new User();
        $userData = $user->where(['id' => $this->session->get('user')])->get();

        return $this->render('pages/profile', [
            "data" => [
                "login" => $userData['login'],
                "email" => $userData['email']
            ]
        ]);
    }

}
