<?php

namespace app\models;

use app\core\Application;
use app\core\DbModel;
use app\core\Model;
use app\models\User;

class SelectModel extends DbModel
{

    public $admin_id = '';
    public $model_id = '';

    public function tableName(): string
    {
        return 'selected_models';
    }

    public function attributes(): array
    {
        return ['admin_id', 'model_id'];
    }

    public function primaryKey(): string
    {
        return 'id';
    }

    public function getId()
    {
        return $this->model_id;
    }

    public function rules(): array
    {
        return [
            'admin_id' => [Model::RULE_REQUIRED],
            'model_id' => [Model::RULE_REQUIRED]
        ];
    }

    public function labels(): array
    {
        return [];
    }

    public function getModel()
    {
        return $this->getAll();
    }

    public function createNew()
    {
        $attributes = $this->attributes();
        $params = array_fill_keys(array_map(fn($attr) => "$attr", $attributes), null);
        var_dump($params);
        foreach ($attributes as $attr) {
            $params["$attr"] = $this->{$attr};
        }
        $user = $this->addNew($params);
        if (!isset($user) || $user == false) {
            $this->addError('error', 'Model wasn`t added successfully');
            return false;
        } else {
            $this->model_id = $user;
            return true;
        }
    }

}