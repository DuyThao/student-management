<?php


namespace App\Models;

use App\Service\BaseService;
use PDO;
use PDOException;

class CoursesModel extends BaseModel
{

    public   $id;
    public   $courses_name;

    function getList()
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

        $sql = "INSERT INTO courses (courses_name)  VALUES (?)";
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
        $sql = "UPDATE courses SET courses_name =? WHERE id =?";
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
    function validate($data)
    {
        $service = new BaseService();
        if (!isset($data['name']) ) {
            return  $service->header_status(400);
        }
    }
   
}
