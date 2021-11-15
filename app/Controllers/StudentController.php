<?php


namespace App\Controllers;

use App\Models\StudentModel;
use App\Config\Smarty\SmartyTemplate;
use App\Service\BaseService;
use App\Service\sspService;
use Core\Http\Route;

class StudentController extends Route
{

    function index()
    {
        $service = new BaseService();
        $token = $service->getCSRFToken();
        $_SESSION['token'] = $token;
        $this->tpl->assign('token', $token);
        $this->tpl->display('student/index.tpl');
    }

    function getDataTable()
    {
        $columns = array(
            array("db" => "id", "dt" => 0),
            array("db" => "name", "dt" => 1),
            array("db" => "time", "dt" => 2),
            array("db" => "average_score", "dt" => 3),
        );
        $select = "std.id, std.name, time,  AVG(a.score) as average_score";
        $joinQuery = "courses_student_mapping as a right join student as std on std.id = a.student_id ";
        $search = $_POST['search']['value'];
        $where = " name like '%$search%' or time like '%$search%' ";
        $groupBy = " std.id, std.name, time ";
        $top = $_POST['top_student'];
        $ssp = new sspService();
        echo json_encode($ssp->SelectGroupBy($_POST, $GLOBALS['config']['mysql'], 'student', 'id', $columns, $joinQuery, $select, $where, $groupBy, $top));
    }

    
    function createStudent()
    {
        $service = new BaseService();
        $service->checkToken($_POST, $_SESSION);
        $model = new StudentModel;
        $model->validate($_POST['data']);
        $data = $this->xssafe($_POST['data']);
        $result =  $model->create($data);
    }
    function deleteStudent()
    {
        $model = new StudentModel;
        $url = $_GET['url'];
        $id = addslashes(explode("/", $url)[1]);
        $data = $_POST;
        $token = array_shift(array_keys($data));
        $ss_token = $_SESSION['token'];
        $service = new BaseService();
        if ($token != $ss_token) {
            $service->header_status(401);
        } else {
            echo $model->delete($id);
        }
    }

    function getItemStudent()
    {
        $model = new StudentModel;
        $url = $_GET['url'];
        $id = explode("/", $url)[1];
        $item = $model->getItem($id);
        echo json_encode($item);
    }

    function updateStudent()
    {
        $service = new BaseService();
        $service->checkToken($_POST, $_SESSION);

        $model = new StudentModel;
        $model->validate($_POST['data']);
        $student = $this->xssafe($_POST['data']);
        echo $model->update($student);
    }
    public function xssafe($data, $encoding = 'UTF-8')
    {
        $service = new BaseService();
        try {
            $student = new StudentModel;
            foreach ($data as $key => $value) {
                $student->$key = htmlspecialchars($value, ENT_QUOTES | ENT_HTML401, $encoding);
            }
            return $student;
        } catch (\ErrorException $ex) {
            return $service->header_status(500);
        }
    }
}
