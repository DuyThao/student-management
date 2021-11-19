<?php


namespace App\Controllers;

use App\Models\StudentModel;
use App\Config\Smarty\SmartyTemplate;
use App\Models\CoursesModel;
use App\Models\StudentOfCoursesModel;
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
        $model = new CoursesModel;
        $courses = $model->getList();

        $this->tpl->assign('courses', $courses);
        $this->tpl->display('student/index.tpl');
    }

    function getDataTable()
    {
        $students=new StudentModel;
        echo $students->getList();
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
        $model->update($student);

        $student_score = new StudentOfCoursesModel;
        $student_score = $student_score->xssafe($_POST['student_score']);
        $student_score->updateStudentScore($student_score);
    }

    function getScore()
    {
        $service = new BaseService();
        $student_courses = new StudentOfCoursesModel;
        $student_courses = $student_courses->xssafe($_POST['data']);

        echo $student_courses->getStudentScore($student_courses);
    }
    function getListCourses()
    {
        $columns = array(
            array("db" => "id", "dt" => 0),
            array("db" => "courses_name", "dt" => 1),
            array("db" => "score", "dt" => 2),
        );

        $student_id=$_POST['student_id'];
        $select ="c.id, courses_name,  d.score";
        $joinQuery = "courses as c left join (select * from courses_student_mapping WHERE courses_student_mapping.student_id=$student_id) as d on c.id=d.courses_id";
        $where = null;
        $ssp = new sspService();
        echo json_encode($ssp->SelectJoin($_POST, $GLOBALS['config']['mysql'], 'courses', 'name', $columns, $joinQuery , $select, $where  ));
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
