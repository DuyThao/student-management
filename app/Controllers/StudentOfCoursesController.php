<?php


namespace App\Controllers;

use App\Models\StudentModel;
use App\Config\Smarty\SmartyTemplate;
use App\Models\CoursesModel;
use App\Models\StudentOfCoursesModel;
use App\Service\BaseService;
use App\Service\sspService;
use Core\Http\Route;

class StudentOfCoursesController extends Route
{

    function index()
    {
        $service = new BaseService();
        $token = $service->getCSRFToken();
        $_SESSION['token'] = $token;
        $this->tpl->assign('token', $token);
        $model = new StudentModel;
        $students= $model->getListStudentAvailable();
        $model = new CoursesModel;
        $courses= $model->getList();

        $this->tpl->assign('students', $students);
        $this->tpl->assign('courses', $courses);
        $this->tpl->display('courses/studentOfCourses.tpl');
    }
  

    function searchStudent()
    {
        $students=new StudentOfCoursesModel;
        echo $students->getList();
    }

   
    function createStudent()
    {
        $service = new BaseService();
        $service->checkToken($_POST, $_SESSION);

            $model = new StudentOfCoursesModel;
            $model->validate($_POST['data']);
            $data = $this->xssafe($_POST['data']);
            $result = $model->create($data);
            echo ($result);
        
    }
    function deleteStudent()
    {
        $model = new StudentOfCoursesModel;
        $url = $_GET['url'];
        $id = addslashes(explode("/", $url)[1]);
        $model->delete($id);
    }

    function updateStudent()
    {
         $service = new BaseService();

        $service->checkToken($_POST, $_SESSION);
            $model = new StudentOfCoursesModel;
            $model->validate($_POST['data']);
            $student = $this->xssafe($_POST['data']);
             $model->update($student);
        
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
