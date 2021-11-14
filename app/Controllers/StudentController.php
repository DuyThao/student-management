<?php


namespace App\Controllers;

use App\Models\StudentModel;
use App\Config\Smarty\SmartyTemplate;
use App\Service\BaseService;
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

    function searchStudent()
    {
        $draw = strip_tags($_POST['draw']);
        $offset = strip_tags($_POST['start']);
        $limit = strip_tags($_POST['length']);

        $search = strip_tags($_POST['search']);
        $column = strip_tags($_POST['column']);
        $type = strip_tags($_POST['type']);
        $top = strip_tags($_POST['top']);

        $list_data = [];
        $model = new StudentModel;
        $list_data = $model->search($offset, $limit, $search, $column, $type, $top);
        if ($list_data == null)
            $list_data = [];
        $count = count($list_data);
        $results = $this->renderDataTable($list_data, $count, $draw);
        echo json_encode($results);
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
