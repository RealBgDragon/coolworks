<?php

namespace app\core;

use Exception;
use PDOException;

abstract class DbModel extends Model
{
    abstract public function tableName(): string;

    abstract public function attributes(): array;

    abstract public function primaryKey(): string;

    public function findOne($where)
    {
        $tableName = static::tableName();
        $attributes = array_keys($where);
        $sql = implode("AND", array_map(fn($attr) => "$attr = :$attr", $attributes));
        $statement = self::prepare("SELECT * FROM $tableName WHERE $sql");
        foreach ($where as $key => $item) {
            $statement->bindValue(":$key", $item);
        }

        $statement->execute();
        return $statement->fetchObject(static::class);
    }

    public function getSpecificInfo($info, $condition = '1=1', $params = [], $table = '')
    {
        if ($table === '') {
            $table = static::tableName();
        }
        $sql = "SELECT $info FROM " . $table . " WHERE " . $condition;
        $statement = self::prepare($sql);
        $statement->execute($params);
        return $statement->fetchAll(\PDO::FETCH_COLUMN);
    }

    public function getAll($sort = 'name', $order = 'asc', $filter = [], $limit = 20, $offset = 0)
    {
        $tableName = static::tableName();
        $sql = "SELECT *, TIMESTAMPDIFF(YEAR, birthday, CURDATE()) AS age FROM $tableName WHERE 1=1";

        // Apply filters
        if (!empty($filter['age_min'])) {
            $sql .= " AND TIMESTAMPDIFF(YEAR, birthday, CURDATE()) >= :age_min";
        }
        if (!empty($filter['age_max'])) {
            $sql .= " AND TIMESTAMPDIFF(YEAR, birthday, CURDATE()) <= :age_max";
        }
        if (!empty($filter['height_min'])) {
            $sql .= " AND height >= :height_min";
        }
        if (!empty($filter['height_max'])) {
            $sql .= " AND height <= :height_max";
        }
        if (!empty($filter['eye_color'])) {
            $sql .= " AND eye_color = :eye_color";
        }
        if (!empty($filter['hair_color']) && is_array($filter['hair_color'])) {
            $a = explode(",", $filter['hair_color'][0]);
            $sql .= " AND hair_color IN ('" . implode("','", array_map('intval', $a)) . "')";
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

        // Apply pagination
        $sql .= " LIMIT :limit OFFSET :offset";

        $statement = self::prepare($sql);

        // Bind filter parameters
        if (!empty($filter['age_min'])) {
            $statement->bindValue(':age_min', $filter['age_min'], \PDO::PARAM_INT);
        }
        if (!empty($filter['age_max'])) {
            $statement->bindValue(':age_max', $filter['age_max'], \PDO::PARAM_INT);
        }
        if (!empty($filter['height_min'])) {
            $statement->bindValue(':height_min', $filter['height_min'], \PDO::PARAM_INT);
        }
        if (!empty($filter['height_max'])) {
            $statement->bindValue(':height_max', $filter['height_max'], \PDO::PARAM_INT);
        }
        if (!empty($filter['eye_color'])) {
            $statement->bindValue(':eye_color', $filter['eye_color'], \PDO::PARAM_STR);
        }

        // Bind pagination parameters
        $statement->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $statement->bindValue(':offset', $offset, \PDO::PARAM_INT);

        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }


    public function countAll($filter = [])
    {
        $tableName = static::tableName();
        $sql = "SELECT COUNT(*) as total FROM $tableName WHERE 1=1";

        // Apply filters
        if (!empty($filter['age_min'])) {
            $sql .= " AND TIMESTAMPDIFF(YEAR, birthday, CURDATE()) >= :age_min";
        }
        if (!empty($filter['age_max'])) {
            $sql .= " AND TIMESTAMPDIFF(YEAR, birthday, CURDATE()) <= :age_max";
        }
        if (!empty($filter['height_min'])) {
            $sql .= " AND height >= :height_min";
        }
        if (!empty($filter['height_max'])) {
            $sql .= " AND height <= :height_max";
        }
        if (!empty($filter['eye_color'])) {
            $sql .= " AND eye_color = :eye_color";
        }
        if (!empty($filter['hair_color']) && is_array($filter['hair_color'])) {
            $placeholders = [];
            foreach ($filter['hair_color'] as $index => $color) {
                $placeholders[] = ":hair_color_$index";
            }
            $sql .= " AND hair_color IN (" . implode(',', $placeholders) . ")";
        }

        $statement = self::prepare($sql);

        // Bind filter parameters
        if (!empty($filter['age_min'])) {
            $statement->bindValue(':age_min', $filter['age_min'], \PDO::PARAM_INT);
        }
        if (!empty($filter['age_max'])) {
            $statement->bindValue(':age_max', $filter['age_max'], \PDO::PARAM_INT);
        }
        if (!empty($filter['height_min'])) {
            $statement->bindValue(':height_min', $filter['height_min'], \PDO::PARAM_INT);
        }
        if (!empty($filter['height_max'])) {
            $statement->bindValue(':height_max', $filter['height_max'], \PDO::PARAM_INT);
        }
        if (!empty($filter['eye_color'])) {
            $statement->bindValue(':eye_color', $filter['eye_color'], \PDO::PARAM_STR);
        }
        if (!empty($filter['hair_color']) && is_array($filter['hair_color'])) {
            foreach ($filter['hair_color'] as $index => $color) {
                $statement->bindValue(":hair_color_$index", $color, \PDO::PARAM_STR);
            }
        }

        $statement->execute();
        return $statement->fetch(\PDO::FETCH_ASSOC)['total'];
    }


    public function addNew($params)
    {
        try {
            $tableName = static::tableName();
            if (empty($params)) {
                return false;
            }

            $columns = implode(", ", array_keys($params));
            $values = implode(", ", array_fill(0, count($params), "?"));


            $sql = "INSERT INTO $tableName (" . $columns . ") VALUES (" . $values . ")";
            $statement = self::prepare($sql);

            $paramNumber = 1;
            foreach ($params as $key => $value) {
                $statement->bindValue($paramNumber++, $value); // Use numeric index 
            }
            $statement->execute();
            return self::getLastId();
        } catch (PDOException $e) {
            return false;
        } catch (Exception $e) {
            return false;
        }
    }

    public function remove($id)
    {
        try {
            $tableName = static::tableName();
            $sql = "DELETE FROM $tableName WHERE model_id = :id";
            $statement = self::prepare($sql);
            $statement->bindValue(':id', $id);

            $statement->execute();
            return true;
        } catch (PDOException $e) {
            $this->addError('error', 'Database error: ' . $e->getMessage());
            return false;
        } catch (Exception $e) {
            $this->addError('error', 'General error: ' . $e->getMessage());
            return false;
        }
    }

    public function removeAll()
    {
        try {
            $tableName = static::tableName();
            $sql = "DELETE FROM $tableName";
            $statement = self::prepare($sql);

            $statement->execute();
            return true;
        } catch (PDOException $e) {
            $this->addError('error', 'Database error: ' . $e->getMessage());
            return false;
        } catch (Exception $e) {
            $this->addError('error', 'General error: ' . $e->getMessage());
            return false;
        }
    }

    public static function prepare($sql)
    {
        return Application::$app->db->pdo->prepare($sql);
    }

    public static function getLastId()
    {
        return Application::$app->db->pdo->lastInsertId();
    }

}