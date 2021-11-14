<?php


namespace App\Models;

use App\Service\BaseService;
use PDO;
use PDOException;

class CoursesModel extends BaseModel
{

    public  int $id;
    public  string $name;


    function getList($offset, $limit)
    {
        $dbh = $this->db->prepare("SELECT * FROM courses");
        $dbh->execute();
        if ($dbh->rowCount()) {
            return  $dbh->fetchAll();
        }
    }
    function create($data)
    {
        $service = new BaseService();

        $sql = "INSERT INTO courses (name)  VALUES (?)";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$data->name]);
            return $service->header_status(200);
        } catch (PDOException $e) {
            return $service->header_status(500);

        }
    }

    function update($data)
    {
        $service = new BaseService();
        $sql = "UPDATE courses SET name =? WHERE id =?";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$data->name, $data->id]);
            return $service->header_status(200);
        } catch (PDOException $e) {
            return $service->header_status(500);
        }
    }

    function delete($id)
    {
        $service = new BaseService();
        try {
            $dbh = $this->db->prepare("DELETE FROM courses WHERE id=?");
            $dbh->execute([$id]);
            $count = $dbh->rowCount();
            if ($count < 1)
                return $service->header_status(500);
            else
                return $service->header_status(200);
        } catch (PDOException $e) {
            return $service->header_status(500);
        }
    }


    function getItem($id)
    {
        $dbh = $this->db->prepare("SELECT * FROM courses WHERE id=?");
        $dbh->execute(array($id));
        if ($dbh->rowCount()) {
            return  $dbh->fetchAll(PDO::FETCH_NUM);
        }
    }
    function search($offset, $limit, $text, $column, $type)
    {
        $dbh = $this->db->prepare("SELECT * FROM courses WHERE name  LIKE '%{$text}%' or courses  LIKE '%{$text}%' or score  LIKE '%{$text}%' ORDER BY {$column} {$type} LIMIT {$offset},{$limit}");
        $dbh->execute();
        if ($dbh->rowCount()) {
            return  $dbh->fetchAll(PDO::FETCH_NUM);
        }
    }

    function countStudent($table, $text, $column, $type)
    {
        $dbh = $this->db->prepare("SELECT COUNT(*) FROM {$table} WHERE name  LIKE '%{$text}%' or courses  LIKE '%{$text}%' or score  LIKE '%{$text}%' ORDER BY {$column} {$type}");
        $dbh->execute();
        $count = $dbh->fetchColumn();
        return $count;
    }

    function top3($table)
    {
        $dbh = $this->db->prepare("SELECT TOP 3 FROM {$table}  ORDER BY score DESC");
        $dbh->execute();
        $count = $dbh->fetchColumn();
        return $count;
    }
}
