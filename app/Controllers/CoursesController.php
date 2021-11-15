<?php


namespace App\Controllers;

use App\Models\CoursesModel;
use App\Service\BaseService;
use App\Service\sspService;
use Core\Http\Route;

class CoursesController extends Route
{

    function index()
    {
        $service = new BaseService();
        $token = $service->getCSRFToken();
        $_SESSION['token'] = $token;
        $this->tpl->assign('token', $token);

        $model = new CoursesModel;
        $data = $model->getList(10, 1);
        if ($data) {
            $this->tpl->assign('list_data', $data);
        }
        $this->tpl->display('courses/index.tpl');
    }

    function listStudent()
    {
        $service = new BaseService();
        $token = $service->getCSRFToken();
        $_SESSION['token'] = $token;
        $this->tpl->assign('token', $token);

        $model = new CoursesModel;
        $data = $model->getList(10, 1);
        if ($data) {
            $this->tpl->assign('list_data', $data);
        }
        $this->tpl->display('courses/index.tpl');
    }

    function getDatatable()
    {
        $columns = array(
            array("db" => "id", "dt" => 0),
            array("db" => "courses_name", "dt" => 1),
        );
        $ssp = new sspService();
        echo json_encode($ssp->simple($_POST, $GLOBALS['config']['mysql'], 'courses', 'id', $columns));
    }


    function createCourses()
    {
        $service = new BaseService();
        $token = $_POST['token'];
        $ss_token = $_SESSION['token'];
        if ($token != $ss_token) {
            $service->header_status(401);
        } else {
            $model = new CoursesModel;
            $model->validate($_POST['data']);

            $data = $this->xssafe($_POST['data']);
            $result =  $model->create($data);
            echo ($result);
        }
    }
    function deleteCourses()
    {
        $service = new BaseService();

        $model = new CoursesModel;
        $url = $_GET['url'];
        $id = addslashes(explode("/", $url)[1]);
        $data = $_POST;
        $token = array_shift(array_keys($data));
        $ss_token = $_SESSION['token'];

        if ($token != $ss_token) {
            $service->header_status(401);
        } else {
            echo $model->delete($id);
        }
    }

    function getItemCourses()
    {
        $model = new CoursesModel;
        $url = $_GET['url'];
        $id = explode("/", $url)[1];
        $item = $model->getItem($id);
        echo json_encode($item);
    }

    function updateCourses()
    {
        $service = new BaseService();
        $token = $_POST['token'];
        $ss_token = $_SESSION['token'];
        if ($token != $ss_token) {
            $service->header_status(401);
        } else {
            $model = new CoursesModel;
            $courses = $this->xssafe($_POST['data']);
            echo $model->update($courses);
        }
    }
    public function saveCourses()
    {
        $_SESSION['courses_id'] = $_POST['courses_id'];
    }
    public function xssafe($data, $encoding = 'UTF-8')
    {
        $courses = new CoursesModel;
        foreach ($data as $key => $value) {
            $courses->$key = htmlspecialchars($value, ENT_QUOTES | ENT_HTML401, $encoding);
        }
        return $courses;
    }
}
