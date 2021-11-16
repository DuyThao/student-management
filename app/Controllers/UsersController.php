<?php


namespace App\Controllers;

use App\Models\UsersModel;

use App\Service\BaseService;
use App\Service\sspService;
use Core\Http\Route;

class UsersController extends Route
{
    function index()
    {
        $service = new BaseService();
        $token = $service->getCSRFToken();
        $_SESSION['token'] = $token;
        $this->tpl->assign('token', $token);
        $this->tpl->display('users/index.tpl');
    }

    function getDatatable()
    {
        $columns = array(
            array("db" => "id", "dt" => 0),
            array("db" => "username", "dt" => 1),
        );
        $ssp = new sspService();
        echo json_encode($ssp->simple($_POST, $GLOBALS['config']['mysql'], 'users', 'id', $columns));
    }

    function loginPage(){
        $this->tpl->display('users/login.tpl');
    }
    function login(){
        $user= new UsersModel;
        $user->username = $_POST['username'];
        $user->password = $_POST['password'];
        if($user->login($user) == true)
            echo "haa";
//            $this->tpl->display('student/index.tpl');
        else
            echo "sai r";
//            $this->tpl->display('users/login.tpl');
    }
    function createUsers(){
        $service = new BaseService();
        $service->checkToken($_POST, $_SESSION);
        $model = new UsersModel;
        $model->validate($_POST['data']);

        $data = $this->xssafe($_POST['data']);
        $result =  $model->create($data);

    }
    public function xssafe($data, $encoding = 'UTF-8')
    {
        $users = new UsersModel;
        foreach ($data as $key => $value) {
            $users->$key = htmlspecialchars($value, ENT_QUOTES | ENT_HTML401, $encoding);
        }
        return $users;
    }
}
