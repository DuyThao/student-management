<?php


namespace App\Models;

use App\Service\BaseService;
use DateTime;
use PDO;
use PDOException;

class StudentOfCoursesModel extends BaseModel
{

    public   $id;
    public   $student_id;
    public   $courses_id;
    public   $score;



    function create($data)
    {
        $service = new BaseService();

        $sql = "INSERT INTO courses_student_mapping (student_id, courses_id, score)  VALUES (? , ? , ? )";
        try {
            $stmt = $this->db->prepare($sql);
            $courses_id= $_SESSION['courses_id'];
            $stmt->execute([$data->student_id, $courses_id, $data->score]);
            return $service->header_status(200);
        } catch (PDOException $e) {
            return  $service->header_status(500, $e);
        }
    }

    function update($data)
    {
        $service = new BaseService();
        $sql = "UPDATE courses_student_mapping SET score =? WHERE id =?";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([ $data->score,$data->id]);
            return $service->header_status(200);
        } catch (PDOException $e) {
            return  $service->header_status(500);
        }
    }

    function delete($id)
    {
        try {
            $service = new BaseService();

            $dbh = $this->db->prepare("DELETE FROM courses_student_mapping WHERE id=?");
            // $dbh->execute([$id]);
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
        $dbh = $this->db->prepare("SELECT * FROM student WHERE id=?");
        $dbh->execute(array($id));
        if ($dbh->rowCount()) {
            return  $dbh->fetchAll(PDO::FETCH_NUM);
        }
    }
    
    function validate($data)
    {
        $service = new BaseService();

        if (!isset($data['name']) || !isset($data['score'])) {
            return  $service->header_status(400);
        }
        if (!preg_match("/^[0-9]*$/", $data['score'])) {
            return  $service->header_status(500);
        }
        return  $service->header_status(500);

    }
}
