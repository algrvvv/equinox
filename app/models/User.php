<?php

namespace Imissher\Equinox\app\models;

use Imissher\Equinox\app\core\database\DbModel;

class User extends DbModel
{
    public string $login = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirm = '';

    protected function rules(): array
    {
        return [
            'login'            => ['required'],
            'email'            => ['email', 'required', 'unique'],
            'password'         => ['required', ['min' => 8]],
            'password_confirm' => ['required', ['min' => 8]]
        ];
    }

    protected function tableName(): string
    {
        return 'users';
    }
}
