<?php

namespace app\core;

use Exception;
use PDOException;
use PDO;

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

    public function getSpecificInfo($info, $condition = '1=1', $params = [], $table = '', $requirement = '', $order = '', $limit = '', $join = '')
    {
        if ($table == '') {
            $table = static::tableName();
        }

        $sql = "SELECT $info FROM " . $table;
        if ($join != '') {
            $sql .= " $join";
        }
        $sql .= " WHERE " . $condition;

        if ($order == 'RAND()') {
            $sql .= " ORDER BY RAND() ";
        } elseif ($order != '') {
            $sql .= " ORDER BY add_date " . ($order === 'ASC' ? 'ASC' : 'DESC');
        }

        if ($limit != '') {
            $sql .= " LIMIT " . (int) $limit;
        }

        $statement = self::prepare($sql);
        $statement->execute($params);

        if ($requirement === '') {
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return $statement->fetchAll(PDO::FETCH_COLUMN);
        }
    }

    public function getAll($sort = '', $order = 'asc', $filter = [], $limit = 20, $offset = 0, $join = '')
    {
        $tableName = $this->tableName();
        if ($join == '') {
            $sql = "SELECT * FROM $tableName WHERE 1=1";
        } else {
            $sql = "SELECT * FROM $tableName $join WHERE 1=1";
        }
        // Apply filters
        foreach ($filter as $key => $value) {
            if (!empty($value)) {
                if (is_array($value)) {
                    // Handle date range for birthday
                    if ($key === 'birthday') {
                        $sql .= " AND ($key BETWEEN ? AND ?)";
                    } else {
                        $placeholders = implode(',', array_fill(0, count($value), '?'));
                        $sql .= " AND $key IN ($placeholders)";
                    }
                } else {
                    $sql .= " AND $key = ?";
                }
            }
        }

        // Apply sorting
        $order = strtoupper($order) === 'DESC' ? 'DESC' : 'ASC';
        if ($sort != '')
            $sql .= " ORDER BY $sort $order";

        // Apply pagination
        $sql .= " LIMIT ? OFFSET ?";

        $statement = self::prepare($sql);

        // Bind filter parameters
        $paramIndex = 1;
        foreach ($filter as $key => $value) {
            if (!empty($value)) {
                if (is_array($value)) {
                    foreach ($value as $item) {
                        $statement->bindValue($paramIndex++, $item);
                    }
                } else {
                    $statement->bindValue($paramIndex++, $value);
                }
            }
        }

        // Bind pagination parameters
        $statement->bindValue($paramIndex++, $limit, PDO::PARAM_INT);
        $statement->bindValue($paramIndex++, $offset, PDO::PARAM_INT);

        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
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
            $eyeColors = explode(',', $filter['eye_color']);
            $placeholders = [];
            foreach ($eyeColors as $index => $color) {
                $placeholders[] = ":eye_color_$index";
            }
            $sql .= " AND eye_color IN (" . implode(',', $placeholders) . ")";
        }

        if (!empty($filter['hair_color'])) {
            $hairColors = explode(',', $filter['hair_color']);
            $placeholders = [];
            foreach ($hairColors as $index => $color) {
                $placeholders[] = ":hair_color_$index";
            }
            $sql .= " AND hair_color IN (" . implode(',', $placeholders) . ")";
        }
        if (!empty($filter['gender'])) {
            if (is_array($filter['gender'])) {
                $genderPlaceholders = [];
                foreach ($filter['gender'] as $index => $gender) {
                    $genderPlaceholders[] = ":gender_$index";
                }
                $sql .= " AND gender IN (" . implode(',', $genderPlaceholders) . ")";
            } else {
                $sql .= " AND gender = :gender";
            }
        }

        $statement = self::prepare($sql);

        // Bind filter parameters
        if (!empty($filter['age_min'])) {
            $statement->bindValue(':age_min', $filter['age_min'], PDO::PARAM_INT);
        }
        if (!empty($filter['age_max'])) {
            $statement->bindValue(':age_max', $filter['age_max'], PDO::PARAM_INT);
        }
        if (!empty($filter['height_min'])) {
            $statement->bindValue(':height_min', $filter['height_min'], PDO::PARAM_INT);
        }
        if (!empty($filter['height_max'])) {
            $statement->bindValue(':height_max', $filter['height_max'], PDO::PARAM_INT);
        }
        if (!empty($filter['eye_color'])) {
            $eyeColors = explode(',', $filter['eye_color']);
            foreach ($eyeColors as $index => $color) {
                $statement->bindValue(":eye_color_$index", $color, PDO::PARAM_INT);
            }
        }
        if (!empty($filter['hair_color'])) {
            $hairColors = explode(',', $filter['hair_color']);
            foreach ($hairColors as $index => $color) {
                $statement->bindValue(":hair_color_$index", $color, PDO::PARAM_INT);
            }
        }
        if (!empty($filter['gender'])) {
            if (is_array($filter['gender'])) {
                foreach ($filter['gender'] as $index => $gender) {
                    $statement->bindValue(":gender_$index", $gender, PDO::PARAM_STR);
                }
            } else {
                $statement->bindValue(':gender', $filter['gender'], PDO::PARAM_STR);
            }
        }
        $statement->execute();
        return $statement->fetch(PDO::FETCH_ASSOC)['total'];
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
            $statement->debugDumpParams();
            $statement->execute();
            return self::getLastId();
        } catch (PDOException $e) {
            return false;
        } catch (Exception $e) {
            return false;
        }
    }

    public function remove($id, $cond)
    {
        try {
            $tableName = static::tableName();
            $sql = "DELETE FROM $tableName WHERE $cond";
            $statement = self::prepare($sql);
            preg_match('/:(\w+)/', $cond, $matches);
            $placeholder = $matches[1]; // e.g., 'model_id' or 'id'
            $statement->bindValue(':' . $placeholder, $id);

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