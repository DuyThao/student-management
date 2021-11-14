<?php


namespace App\Models;

use App\Service\BaseService;
use DateTime;
use PDO;
use PDOException;

class StudentOfCoursesModel extends BaseModel
{

    public  int $id;
    public  string $student_id;
    public  string $courses_id;
    public  string $score;


    function getList($offset, $limit)
    {
        $dbh = $this->db->prepare("SELECT * FROM courses_student_mapping");
        $dbh->execute();
        if ($dbh->rowCount()) {
            return $dbh->fetchAll(PDO::FETCH_NUM);
        }
    }
    function create($data)
    {
        $service = new BaseService();

        $sql = "INSERT INTO courses_student_mapping (student_id, courses_id, score)  VALUES (? , ? , ? )";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$data->student_id, $data->courses_id, $data->score]);
            return $service->header_status(200);
        } catch (PDOException $e) {
            return  $service->header_status(500);
        }
    }

    function update($data)
    {
        $service = new BaseService();

        $sql = "UPDATE student SET name =? , courses =? , score =? ,  time =? WHERE id =?";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$data->name, $data->courses, $data->score, $data->time, $data->id]);
            return $service->header_status(200);
        } catch (PDOException $e) {
            return  $service->header_status(500);
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
    function search($offset, $limit, $text, $column, $type, $top)
    {
        if ($top == "true") { //SELECT st.* FROM (SELECT DISTINCT score FROM student ORDER BY score DESC LIMIT 3 )st1 JOIN student st ON st.score = st1.score ORDER BY st.score DESC;
            $dbh = $this->db->prepare("SELECT * FROM student  ORDER BY score DESC LIMIT 3 ");
        } else {
            $dbh = $this->db->prepare("SELECT * FROM student WHERE name  LIKE '%{$text}%' or courses  LIKE '%{$text}%' or score  LIKE '%{$text}%' ORDER BY {$column} {$type} LIMIT {$offset},{$limit}");
        }
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
    function validate($data)
    {
        $service = new BaseService();

        if (!isset($data['name']) || !isset($data['courses'])|| !isset($data['time'])) {
            return  $service->header_status(400);
        }
        if (!preg_match("/^[0-9]*$/", $data['score'])) {
            return  $service->header_status(500);

        }
        return  $service->header_status(500);

    }
}
