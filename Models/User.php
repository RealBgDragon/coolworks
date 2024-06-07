<?php

namespace app\models;

use app\core\Model;
use app\core\UserModel;

class User extends UserModel
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 2;

    public string $firstname = '';
    public string $lastname = '';
    public string $email = '';
    public int $status = self::STATUS_INACTIVE;
    public string $password = '';
    public string $rePassword = '';

    public function tableName(): string
    {
        return 'users';
    }

    public function primaryKey(): string
    {
        return 'id';
    }

    public function rules(): array
    {
        return [
            'firstname' => [Model::RULE_REQUIRED],
            'lastname' => [Model::RULE_REQUIRED],
            'email' => [
                Model::RULE_REQUIRED,
                Model::RULE_EMAIL,
                [
                    Model::RULE_UNIQUE,
                    'class' => self::class
                ]
            ],
            'password' => [Model::RULE_REQUIRED, [Model::RULE_MIN, 'min' => 8]],
            'rePassword' => [Model::RULE_REQUIRED, [Model::RULE_MATCH, 'match' => 'password']]
        ];
    }

    public function attributes(): array
    {
        return ['firstname', 'lastname', 'email', 'password', 'status'];
    }

    public function labels(): array
    {
        return [
            'firstname' => 'First name',
            'lastname' => 'Last name',
            'email' => 'Email',
            'password' => 'Password',
            'rePassword' => 'Repeat password',
        ];
    }

    public function getDisplayName(): string
    {
        return $this->firstname . ' ' . $this->lastname;
    }
}