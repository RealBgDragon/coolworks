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
    public $selection_id = '';
    public $selection_name = null;
    public $tableName = 'selected_models';
    public function tableName(): string
    {
        return $this->tableName;
    }

    public function setTableName($tableName)
    {
        $this->tableName = $tableName;
    }

    public function attributes(): array
    {
        return ['admin_id', 'model_id', 'selection_name'];
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
            'admin_id' => [self::RULE_REQUIRED],
            'model_id' => [self::RULE_REQUIRED],
            'selection_name' => [self::RULE_REQUIRED]
        ];
    }

    public function labels(): array
    {
        return [
            'selection_name' => 'Selection Name'
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
        if (!isset($user) || $user == false) {
            $this->addError('error', 'Model wasn`t added successfully');
            return false;
        } else {
            $this->model_id = $user;
            return true;
        }
    }

    public function removeSelection()
    {
        $model_id = $this->model_id;
        if (empty($model_id)) {
            $this->addError('error', 'Model ID is not set');
            return false;
        }
        $condition = "model_id = :model_id";
        if ($this->remove($model_id, $condition)) {
            return true;
        } else {
            $this->addError('error', 'Model wasn`t removed successfully');
            return false;
        }
    }

    public function deleteSelection()
    {
        $id = $this->selection_id;
        $tableName = 'saved_selections';
        $this->setTableName($tableName);
        $condition = "id = :id";

        return $this->remove($id, $condition);
    }

    public function getSelectedModelIds($selection_name = '')
    {
        $info = 'model_id';
        $admin_id = $_SESSION['admin'];
        $this->setTableName('selected_models');

        if ($selection_name == '') {
            $params = ['admin_id' => $admin_id];
            $cond = "1=1";
            $table = '';
            $condition = "admin_id = :admin_id AND " . $cond;
        } else {
            $table = 'saved_selections';
            $cond = "name = :selection_name";
            $params['selection_name'] = $selection_name;

            $selection_ids = $this->getSpecificInfo('id', $cond, $params, $table, 'column');

            $info = 'model_id';
            $table = 'selection_items';
            $condition = "selection_id IN (" . implode(',', array_fill(0, count($selection_ids), '?')) . ")";
            /* $cond = '1=1'; */
            $params = $selection_ids;
        }


        return $this->getSpecificInfo($info, $condition, $params, $table, 'column');
    }

    public function getSelections()
    {
        $table = 'saved_selections';
        $this->setTableName($table);
        return $this->getAll();
    }
    // New method to get detailed info about selected models
    public function getModelsByIds($ids, $join = '')
    {
        if (empty($ids)) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($ids), '?'));

        $sql = "SELECT m.*, GROUP_CONCAT(DISTINCT mt.talant_id SEPARATOR ', ') AS talant_id, 
        GROUP_CONCAT(DISTINCT ml.language_id SEPARATOR ', ') AS language_id, TIMESTAMPDIFF(YEAR, m.birthday, CURDATE()) AS age 
            FROM models m";

        if (!empty($join)) {
            $sql .= " $join";
        }

        $sql .= " WHERE m.model_id IN ($placeholders) GROUP BY m.model_id";

        $statement = self::prepare($sql);
        $paramNumber = 1;
        foreach ($ids as $id) {
            $statement->bindValue($paramNumber++, $id);
        }

        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }
    /* public function getModelsByIds($ids)
    {
        if (empty($ids)) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $sql = "SELECT *, TIMESTAMPDIFF(YEAR, birthday, CURDATE()) AS age FROM model WHERE model_id IN ($placeholders)";

        $statement = self::prepare($sql);

        $paramNumber = 1;
        foreach ($ids as $id) {
            $statement->bindValue($paramNumber++, $id);
        }

        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    } */


    public function getRandomModels()
    {
        $photoModel = new PhotoModel();
        $info = '*';
        $join = 'LEFT JOIN c_models_talants mt ON models.model_id = mt.model_id ';
        $randomModels = $this->getSpecificInfo($info, '1=1', [], 'models', '', 'RAND()', 10, $join);
        foreach ($randomModels as &$model) {
            $model['age'] = $photoModel->calculateAge($model['birthday']);
        }
        return $randomModels;
    }

    public function saveSelection()
    {
        $admin_id = $_SESSION['admin'];
        $modelIds = $this->getSelectedModelIds();

        $sql = "INSERT INTO saved_selections (name, admin_id, iterations) VALUES (:name, :admin_id, 1)";

        $statement = self::prepare($sql);
        $statement->bindParam(':name', $this->selection_name);
        $statement->bindParam(':admin_id', $admin_id);
        $statement->execute();
        $selection_id = self::getLastId();
        foreach ($modelIds as $modelId) {

            $sql = "INSERT INTO selection_items (selection_id, model_id) VALUES (:selection_id, :model_id)";
            $statement = self::prepare($sql);
            $statement->bindParam(':selection_id', $selection_id);
            $statement->bindParam(':model_id', $modelId);
            $statement->execute();
        }

        $this->removeAll();

        return true;
    }

    public function transferSelection($selection_name)
    {
        // Retrieve model IDs from the selection
        $modelIds = $this->getSelectedModelIds($selection_name);

        if (empty($modelIds)) {
            $this->addError('error', 'No models found in the selection.');
            return false;
        }

        // Remove all existing models for the current admin from the selected_models table
        $admin_id = $_SESSION['admin'];
        $params = ['admin_id' => $admin_id];
        $condition = "admin_id = :admin_id";
        $this->remove($admin_id, $condition);
        // Insert new models into the selected_models table
        foreach ($modelIds as $modelId) {
            $params = [
                'admin_id' => $admin_id,
                'model_id' => $modelId,
                'selection_name' => $selection_name
            ];

            $this->addNew($params);
        }

        return true;
    }


}
