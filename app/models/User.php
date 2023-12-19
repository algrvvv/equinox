<?php

namespace Imissher\Equinox\app\models;

use Imissher\Equinox\app\core\Model;

class User extends Model
{
    public string $login = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirm = '';

    protected function rules(): array
    {
        return [
            'login'            => ['required'],
            'email'            => ['email', 'required'],
            'password'         => ['required', ['min' => 8]],
            'password_confirm' => ['required', ['min' => 8]]
        ];
    }
}
