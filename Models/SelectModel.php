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
    public $selection_name = null;

    public function tableName(): string
    {
        return 'selected_models';
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
        $id = $this->model_id;
        if (empty($id)) {
            $this->addError('error', 'Model ID is not set');
            return false;
        }

        if ($this->remove($id)) {
            return true;
        } else {
            $this->addError('error', 'Model wasn`t removed successfully');
            return false;
        }
    }

    public function getSelectedModelIds($selection_name = '')
    {
        $info = 'model_id';
        $admin_id = $_SESSION['admin'];
        if ($selection_name === '') {
            $cond = "selection_name IS NULL";
        } else {
            $cond = "selection_name = :selection_name";
        }
        $condition = "admin_id = :admin_id AND $cond";
        $params = ['admin_id' => $admin_id, 'selection_name' => $selection_name];
        return $this->getSpecificInfo($info, $condition, $params);
    }

    public function getSelections()
    {
        $info = 'selection_name';
        return $this->getSpecificInfo($info);
    }
    // New method to get detailed info about selected models
    public function getModelsByIds($ids, $sort = 'name', $order = 'asc', $filter = [])
    {
        if (empty($ids)) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $sql = "SELECT *, TIMESTAMPDIFF(YEAR, birthday, CURDATE()) AS age FROM model WHERE model_id IN ($placeholders)";

        // Apply filters
        if (!empty($filter['age_min'])) {
            $sql .= " AND TIMESTAMPDIFF(YEAR, birthday, CURDATE()) >= ?";
            $ids[] = $filter['age_min'];
        }
        if (!empty($filter['age_max'])) {
            $sql .= " AND TIMESTAMPDIFF(YEAR, birthday, CURDATE()) <= ?";
            $ids[] = $filter['age_max'];
        }
        if (!empty($filter['height_min'])) {
            $sql .= " AND height >= ?";
            $ids[] = $filter['height_min'];
        }
        if (!empty($filter['height_max'])) {
            $sql .= " AND height <= ?";
            $ids[] = $filter['height_max'];
        }

        // Apply sorting
        $allowedSortColumns = ['name', 'age', 'height'];
        $sort = in_array($sort, $allowedSortColumns) ? $sort : 'name';
        $order = strtoupper($order) === 'DESC' ? 'DESC' : 'ASC';

        if ($sort === 'age') {
            $sql .= " ORDER BY TIMESTAMPDIFF(YEAR, birthday, CURDATE()) $order";
        } else {
            $sql .= " ORDER BY $sort $order";
        }

        $statement = self::prepare($sql);

        $paramNumber = 1;
        foreach ($ids as $id) {
            $statement->bindValue($paramNumber++, $id);
        }

        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function saveSelection()
    {
        $modelIds = $this->getSelectedModelIds();

        $sql = "UPDATE " . $this->tableName() . " SET selection_name = :selection_name WHERE selection_name IS NULL AND model_id IN (" . implode(',', $modelIds) . ")";
        $statement = self::prepare($sql);

        $statement->bindValue(':selection_name', $this->selection_name);

        return $statement->execute();
    }
}
