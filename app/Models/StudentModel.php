<?php


namespace App\Models;

use App\Service\BaseService;
use App\Service\sspService;
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

    function getListStudentAvailable()
    {
        $dbh = $this->db->prepare('SELECT * 
                                    FROM student as std LEFT JOIN courses_student_mapping as c on std.id=c.id
                                    WHERE c.student_id is null' );
        $dbh->execute();
        if ($dbh->rowCount()) {
            return $dbh->fetchAll(PDO::FETCH_NUM);
        }
    }
    function create($data)
    {
        $service = new BaseService();

        $sql = 'INSERT INTO student (name, time)  VALUES (? , ? )';
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

        $sql = 'UPDATE student SET name =? ,   time =? WHERE id =?';

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

            $dbh = $this->db->prepare('DELETE FROM student WHERE id=?');
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
        $dbh = $this->db->prepare('SELECT * FROM student WHERE id=?');
        $dbh->execute(array($id));
        if ($dbh->rowCount()) {
            return  $dbh->fetchAll(PDO::FETCH_NUM);
        }
    }
    function getList(){
        $columns = array(
            array('db' => 'id', 'dt' => 0),
            array('db' => 'name', 'dt' => 1),
            array('db' => 'time', 'dt' => 2),
            array('db' => 'average_score', 'dt' => 3),
        );
        $select = 'std.id, std.name, time,  AVG(a.score) as average_score';
        $joinQuery = 'courses_student_mapping as a right join student as std on std.id = a.student_id ';
        $search = $_POST['search']['value'];
        $where = " name like '%$search%' or time like '%$search%' ";
        $groupBy = ' std.id, std.name, time ';
        $top = $_POST['top_student'];
        $ssp = new sspService();
        return json_encode($ssp->SelectGroupBy($_POST, $GLOBALS['config']['mysql'], 'student', 'id', $columns, $joinQuery, $select, $where, $groupBy, $top));
    }
   
    function validate($data)
    {
        $service = new BaseService();

        if (!isset($data['name']) || !isset($data['time'])) {
            return  $service->header_status(400);
        }
    }
}
