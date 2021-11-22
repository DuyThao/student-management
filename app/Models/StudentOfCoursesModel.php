<?php


namespace App\Models;

use App\Service\BaseService;
use App\Service\sspService;
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

        $sql = 'INSERT INTO courses_student_mapping (student_id, courses_id, score)  VALUES (? , ? , ? )';
        try {
            $stmt = $this->db->prepare($sql);
            $courses_id = $_SESSION['courses_id'];
            $stmt->execute([$data->student_id, $courses_id, $data->score]);
            return $service->header_status(200);
        } catch (PDOException $e) {
            return $service->header_status_code(500, $e->getMessage());
        }
    }

    function update($data)
    {
        $service = new BaseService();
        $sql = 'UPDATE courses_student_mapping SET score =? WHERE id =?';
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$data->score, $data->id]);
            return $service->header_status(200);
        } catch (PDOException $e) {
            return $service->header_status_code(500, $e->getMessage());
        }
    }

    function updateStudentScore($data)
    {
        $service = new BaseService();

        if ($this->getStudentScore($data) != null) {

            $sql = 'UPDATE courses_student_mapping SET score =? WHERE student_id =? AND courses_id=?';
            try {
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$data->score, $data->student_id, $data->courses_id]);
                return $service->header_status(200);
            } catch (PDOException $e) {
                return $service->header_status_code(500, $e->getMessage());
            }
        } else {
            $sql = 'INSERT INTO courses_student_mapping (student_id, courses_id, score)  VALUES (? , ? , ? )';
            try {
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$data->student_id, $data->courses_id, $data->score]);
                return $service->header_status(200);
            } catch (PDOException $e) {
                return $service->header_status_code(500, $e->getMessage());
            }
        }
    }

    function delete($id)
    {
        try {
            $service = new BaseService();

            $dbh = $this->db->prepare('DELETE FROM courses_student_mapping WHERE id=?');
            $dbh->execute([$id]);
            $count = $dbh->rowCount();
            if ($count < 1)
                return $service->header_status(500);
            else
                return $service->header_status(200);
        } catch (PDOException $e) {
            return $service->header_status_code(500, $e->getMessage());
        }
    }


    function getStudentScore($student_courses)
    {
        $service = new BaseService();

        $dbh = $this->db->prepare('SELECT * FROM courses_student_mapping WHERE student_id=? AND courses_id=?');
        $dbh->execute([$student_courses->student_id, $student_courses->courses_id]);
        $count = $dbh->rowCount();
        if ($dbh->rowCount() > 0) {
            return  json_encode($dbh->fetchAll());
        } else
            $service->header_status(500);
    }
    function getList(){
        $columns = array(
            array("db" => "id", "dt" => 0),
            array("db" => "name", "dt" => 1),
            array("db" => "courses_name", "dt" => 2),
            array("db" => "score", "dt" => 3),
           
        );
        $id= $_POST['id'];
        $_SESSION['courses_id'] = $id;
        $select ="a.id, std.name, courses_name,  a.score";
        $joinQuery = "courses_student_mapping as a inner join student as std on std.id = a.student_id inner join courses as c on c.id=a.courses_id";
        $where = "courses_id=$id";
        $ssp = new sspService();
        return json_encode($ssp->SelectJoin($_POST, $GLOBALS['config']['mysql'], 'student', 'name', $columns, $joinQuery , $select, $where  ));
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
    public function xssafe($data, $encoding = 'UTF-8')
    {
        $service = new BaseService();
        try {
            $student = new StudentOfCoursesModel;
            foreach ($data as $key => $value) {
                $student->$key = htmlspecialchars($value, ENT_QUOTES | ENT_HTML401, $encoding);
            }
            return $student;
        } catch (\ErrorException $ex) {
            return $service->header_status(500);
        }
    }
}
