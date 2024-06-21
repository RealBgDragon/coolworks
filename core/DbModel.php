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

    public function getAll()
    {
        $tableName = static::tableName();
        $sql = "SELECT * FROM $tableName";
        $statement = self::prepare($sql);

        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
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
            /* $this->id =  */
            return self::getLastId();
        } catch (PDOException $e) {
            return false;
        } catch (Exception $e) {
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