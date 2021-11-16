<?php


namespace App\Models;

use App\Service\BaseService;
use DateTime;
use PDO;
use PDOException;

class StudentModel extends BaseModel
{

    public   $id;
    public   $name;
    public   $courses;
    public   $score;
    public   $time;


    function getList()
    {
        $dbh = $this->db->prepare("SELECT * FROM student");
        $dbh->execute();
        if ($dbh->rowCount()) {
            return $dbh->fetchAll(PDO::FETCH_NUM);
        }
    }
    function getListStudentAvailable()
    {
        $dbh = $this->db->prepare("SELECT * 
                                    FROM student as std 
                                    WHERE std.id  NOT IN
                                        (SELECT student_id 
                                        FROM courses_student_mapping where courses_id =?)" );
        $dbh->execute([$_SESSION['courses_id']]);
        if ($dbh->rowCount()) {
            return $dbh->fetchAll(PDO::FETCH_NUM);
        }
    }
    function create($data)
    {
        $service = new BaseService();

        $sql = "INSERT INTO student (name, time)  VALUES (? , ? )";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$data->name, $data->time]);
            return $service->header_status(200);
        } catch (PDOException $e) {
            return  $service->header_status_code(500, $e->getMessage());
        }
    }

    function update($data)
    {
        $service = new BaseService();

        $sql = "UPDATE student SET name =? ,   time =? WHERE id =?";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$data->name,  $data->time, $data->id]);
            return $service->header_status(200);
        } catch (PDOException $e) {
            return  $service->header_status_code(500, $e->getMessage());
        }
    }

    function delete($id)
    {
        try {
            $service = new BaseService();

            $dbh = $this->db->prepare("DELETE FROM student WHERE id=?");
            $dbh->execute([$id]);
            $count = $dbh->rowCount();
            if ($count < 1)
                return $service->header_status(500);
            else
                return $service->header_status(200);
        } catch (PDOException $e) {
            return $service->header_status_code(500,$e->getMessage());
        }
    }


    function getItem($id)
    {
        $dbh = $this->db->prepare("SELECT * FROM student WHERE id=?");
        $dbh->execute(array($id));
        if ($dbh->rowCount()) {
            return  $dbh->fetchAll(PDO::FETCH_NUM);
        }
    }
   
    function validate($data)
    {
        $service = new BaseService();

        if (!isset($data['name']) || !isset($data['time'])) {
            return  $service->header_status(400);
        }
    }
}
