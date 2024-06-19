<?php

namespace app\models;

use app\core\Application;
use app\core\Model;
use app\models\User;

class LoginForm extends Model
{

    public string $email = '';
    public string $password = '';

    public function rules(): array
    {
        return [
            'email' => [Model::RULE_REQUIRED, Model::RULE_EMAIL],
            'password' => [Model::RULE_REQUIRED]
        ];
    }

    public function labels(): array
    {
        return [
            'email' => 'Email',
            'password' => 'Password'
        ];
    }

    public function login()
    {
        $user = (new User())->findOne(['email' => $this->email]);
        if (!$user) {
            $this->addError('email', 'User does not exist');
            return false;
        }
        if (!password_verify($this->password, $user->password)) {
            $this->addError('password', 'Password is incorrect');
            return false;
        }
        /* if (!($this->password == $user->password)) {
            $this->addError('password', 'Password is incorrect');
            return false;
        } */
        $type = $user->getAdmin();
        return Application::$app->login($user, $type);
    }
}