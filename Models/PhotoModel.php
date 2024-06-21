<?php

namespace app\models;

use app\core\Application;
use app\core\DbModel;
use app\core\Model;
use app\models\User;

class PhotoModel extends DbModel
{

    public $model_id = '';
    public $first_name = '';
    public $last_name = '';
    public $image_url = '';
    public $age = '';

    public function tableName(): string
    {
        return 'model';
    }

    public function attributes(): array
    {
        return ['first_name', 'last_name', 'image_url', 'age'];
    }

    public function primaryKey(): string
    {
        return 'model_id';
    }

    public function getId()
    {
        return $this->model_id;
    }

    public function rules(): array
    {
        return [
            'first_name' => [Model::RULE_REQUIRED],
            'last_name' => [Model::RULE_REQUIRED],
            /* 'image_url' => [Model::RULE_REQUIRED], */
            'age' => [Model::RULE_REQUIRED]
        ];
    }

    public function labels(): array
    {
        return [
            'first_name' => 'First name',
            'last_name' => 'Last name',
            'image_url' => 'Image',
            'age' => 'Age'
        ];
    }

    public function getModel()
    {
        return $this->getAll();
    }

    public function createNew()
    {
        $attributes = $this->attributes();
        $params = array_fill_keys(array_map(fn($attr) => "$attr", $attributes), null);
        foreach ($attributes as $attr) {
            $params["$attr"] = $this->{$attr};
        }
        $user = $this->addNew($params); //contains the userId
        if (!isset($user)) {
            $this->addError('error', 'Model wasn`t added successfully');
            return false;
        } else {
            $this->model_id = $user;
            return true;
        }
    }

}