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
    public $eye_color = 0; // New column for eye color
    public $hair_color = 0; // New column for hair color

    public function tableName(): string
    {
        return 'model';
    }

    public function attributes(): array
    {
        return ['name', 'phone', 'birthday', 'weight', 'height', 'eye_color', 'hair_color'];
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
        $user = $this->addNew($params);
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
        $this->eye_color = $eyeColor;
    }

    public function getEyeColor()
    {
        return $this->eye_color;
    }

    public function setHairColor($hairColor)
    {
        $this->hair_color = $hairColor;
    }

    public function getHairColor()
    {
        return $this->hair_color;
    }

    function getImagePath($modelId)
    {
        $baseDir = dirname(__DIR__) . "/public/uploads/{$modelId}/";
        $pngPath = $baseDir . "img.png";
        $jpgPath = $baseDir . "img.jpg";

        if (file_exists($pngPath)) {
            return "/uploads/{$modelId}/img.png";
        } elseif (file_exists($jpgPath)) {
            return "/uploads/{$modelId}/img.jpg";
        } else {
            return "/uploads/{$modelId}/img.png";
        }
    }

    function getAllImagePaths($modelId)
    {
        $baseDir = dirname(__DIR__) . "/public/uploads/{$modelId}/";
        $imagePaths = [];

        // Check if the directory exists
        if (is_dir($baseDir)) {
            // Get all files in the directory
            $files = scandir($baseDir);

            // Define allowed image extensions
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

            foreach ($files as $file) {
                $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));

                // Check if the file is an image
                if (in_array($extension, $allowedExtensions)) {
                    // Add the full path to the image paths array
                    $imagePaths[] = "/uploads/{$modelId}/" . $file;
                }
            }
        }

        // Join the image paths with commas and return as a single string
        return implode(',', $imagePaths);
    }

}
