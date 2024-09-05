<?php

namespace app\models;

use app\core\Application;
use app\core\form\ModelOptions;
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
    public $eye_color = 0;
    public $hair_color = 0;
    public $talant = 0;
    public $language = 0;

    public function tableName(): string
    {
        return 'model';
    }

    public function attributes(): array
    {
        return ['name', 'phone', 'birthday', 'weight', 'height', 'eye_color', 'hair_color', 'talant', 'language'];
    }

    public function labels(): array
    {
        return [
            'name' => 'Name',
            'phone' => 'Phone',
            'birthday' => 'Date of birth',
            'weight' => 'Weight',
            'height' => 'Height',
            'eye_color' => 'Eye Color',
            'hair_color' => 'Hair Color',
            'talant' => 'Talant',
            'language' => 'Language'
        ];
    }

    public function primaryKey(): string
    {
        return 'model_id';
    }

    public function getAllModels($sort = 'name', $order = 'asc', $filter = [], $limit = 20, $offset = 0)
    {
        $additionalFilters = [];
        $modelOptions = new ModelOptions();

        // Convert age range to birthday filter
        if (isset($filter['age_min']) && isset($filter['age_max'])) {
            $currentYear = date('Y');
            $ageMinYear = $currentYear - $filter['age_max'];
            $ageMaxYear = $currentYear - $filter['age_min'];
            $additionalFilters['birthday'] = ["$ageMinYear-01-01", "$ageMaxYear-12-31"];
        }

        // Pass other filters directly
        if (!empty($filter['height_min'])) {
            $additionalFilters['height'][] = $filter['height_min'];
        }
        if (!empty($filter['height_max'])) {
            $additionalFilters['height'][] = $filter['height_max'];
        }
        if (!empty($filter['eye_color'])) {
            $additionalFilters['eye_color'] = array_map('intval', explode(',', $filter['eye_color'][0]));
        }

        // Split and convert hair_color to an array of integers
        if (!empty($filter['hair_color'])) {
            $additionalFilters['hair_color'] = array_map('intval', explode(',', $filter['hair_color'][0]));
        }
        if (!empty($filter['language'])) {
            $additionalFilters['language'] = array_map('intval', explode(',', $filter['language'][0]));
            foreach ($additionalFilters['language'] as $lan) {
                switch ($lan) {
                    case $modelOptions::LANGUAGE_BULGARIAN:
                        $lan |= $modelOptions::LANGUAGE_ENGLISH;
                        break;
                    case $modelOptions::LANGUAGE_ENGLISH:
                        $lan |= $modelOptions::LANGUAGE_BULGARIAN;
                        break;
                }
                array_push($additionalFilters['language'], $lan);
            }
        }
        if (!empty($filter['talant'])) {
            $additionalFilters['talant'] = array_map('intval', explode(',', $filter['talant'][0]));
        }

        $models = $this->getAll($sort, $order, $additionalFilters, $limit, $offset);

        foreach ($models as &$model) {
            $model['age'] = $this->calculateAge($model['birthday']);
        }
        return $models;
    }


    public function calculateAge($birthday)
    {
        $birthDate = new \DateTime($birthday);
        $currentDate = new \DateTime();
        $age = $currentDate->diff($birthDate)->y;
        return $age;
    }

    public function getId()
    {
        return $this->model_id;
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

    public function setTalant($talant)
    {
        $this->talant = $talant;
    }

    public function setLanguage($languages)
    {
        $languageBitmask = 0;
        foreach ($languages as $language) {
            $languageBitmask |= $language;
        }
        $this->language = $languageBitmask;
    }

    public function getHairColor()
    {
        return $this->hair_color;
    }

    public function getImagePath($modelId)
    {
        $baseDir = dirname(__DIR__) . "/uploads/{$modelId}/";
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

    public function getAllImagePaths($modelId)
    {
        $baseDir = dirname(__DIR__) . "/uploads/{$modelId}/";
        $imagePaths = [];

        if (is_dir($baseDir)) {
            $files = scandir($baseDir);
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

            foreach ($files as $file) {
                $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                if (in_array($extension, $allowedExtensions)) {
                    $imagePaths[] = "/uploads/{$modelId}/" . $file;
                }
            }
        }

        return implode(',', $imagePaths);
    }

    public function getLastModels($info = 'name', $limit = 10)
    {
        $tableName = $this->tableName();
        $models = $this->getSpecificInfo($info, '1=1', [], '', '', 'DESC', $limit);

        return $models;
    }

}