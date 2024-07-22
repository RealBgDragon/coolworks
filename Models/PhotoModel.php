<?php

namespace app\models;

use app\core\Application;
use app\core\DbModel;
use app\core\Model;
use app\models\User;

class PhotoModel extends DbModel
{
    public $model_id = '';
    public $name = '';
    public $phone = '';
    public $birthday = '';
    public $weight = '';
    public $height = '';
    public $options = 0; // This will store eye color and hair color options as a single integer

    public function tableName(): string
    {
        return 'model';
    }

    public function attributes(): array
    {
        return ['name', 'phone', 'birthday', 'weight', 'height', 'options'];
    }

    public function getId()
    {
        return $this->model_id;
    }

    public function primaryKey(): string
    {
        return 'model_id';
    }

    public function rules(): array
    {
        return [
            'name' => [Model::RULE_REQUIRED],
            'phone' => [Model::RULE_REQUIRED],
            'height' => [Model::RULE_REQUIRED],
            'birthday' => [Model::RULE_REQUIRED],
            'weight' => [Model::RULE_REQUIRED],
        ];
    }

    public function labels(): array
    {
        return [
            'name' => 'Name',
            'phone' => 'Phone',
            'height' => 'Height',
            'weight' => 'Weight',
            'birthday' => 'Date of birth'
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

    public function setEyeColor($eyeColor)
    {
        $this->options = ($this->options & ~7) | $eyeColor; // Clear eye color bits and set new value
    }

    public function getEyeColor()
    {
        return $this->options & 7; // Get eye color bits
    }

    public function setHairColor($hairColor)
    {
        $this->options = ($this->options & ~56) | $hairColor; // Clear hair color bits and set new value
    }

    public function getHairColor()
    {
        return $this->options & 56; // Get hair color bits
    }

}