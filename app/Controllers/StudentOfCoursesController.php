<?php


namespace App\Controllers;

use App\Models\StudentModel;
use App\Config\Smarty\SmartyTemplate;
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

        $model = new StudentOfCoursesModel;
        $data = $model->getList(10, 1);
        if ($data) {
            $this->tpl->assign('list_data', $data);
        }
        $this->tpl->display('courses/studentOfCourses.tpl');
    }

    function searchStudent()
    {
        $columns = array(
            array("db" => "id", "dt" => 0),
            array("db" => "name", "dt" => 1),
            array("db" => "courses", "dt" => 2),
            array("db" => "score", "dt" => 3),
            array("db" => "time", "dt" => 4)
        );
        // , "formatter" => function ($d, $row) {
        //     return date("d-m-Y", strtotime($d));
        // }
        $ssp = new sspService();
        echo json_encode($ssp->simple($_POST, $GLOBALS['config']['mysql'], 'student', 'id', $columns));
    }

    function renderDataTable($list_data, $count, $draw)
    {
        foreach ($list_data as $key => $value) {
            array_push($list_data[$key], [
                '<div id="update_' . $list_data[$key][0] . '" class="btn btn-primary" data-toggle="modal" data-target="#edit_modal" 
                data-whatever="@getbootstrap" onclick="getItem(' . $list_data[$key][0] . ')" ><i class="far fa-edit" aria-hidden="true"></i></div> 
                <button id="delete_' . $list_data[$key][0]  . '" type="button" class="btn btn-danger" onclick="deleteStudent(' . $list_data[$key][0] . ')" ><i class="fa fa-trash"></i></button>  '
            ]);
        }

        $results = array(
            "draw" => intval($draw),
            "recordsTotal" => $count,
            "recordsFiltered" => $count,
            "aaData" => $list_data
        );
        return $results;
    }
    function createStudent()
    {
        $service = new BaseService();
        $token = $_POST['token'];
        $ss_token = $_SESSION['token'];
        if ($token != $ss_token) {
            $service->header_status(401);
        } else {
            $model = new StudentModel;

            $data = $this->xssafe($_POST['data']);
            $result =  $model->create($data);

            echo ($result);
        }
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
        $token = $_POST['token'];
        $ss_token = $_SESSION['token'];
        if ($token != $ss_token) {
            $service->header_status(401);
        } else {
            $model = new StudentModel;
            $model->validate($_POST['data']);
            $student = $this->xssafe($_POST['data']);
            echo $model->update($student);
        }
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
