<?php


namespace App\Models;

use App\Service\BaseService;
use PDO;
use PDOException;

class UsersModel extends BaseModel
{

    public   $id;
    public   $username;
    public   $password;


    function create($data)
    {
        $service = new BaseService();

        $sql = 'INSERT INTO users (username, password, email, phone, create_at ) VALUES (?, ?, ?, ?, ?)';
        try {
            $stmt = $this->db->prepare($sql);
            $password = password_hash(md5($data->password), PASSWORD_DEFAULT);

            $stmt->execute([$data->username, $password, $data->username, $data->username, '2021/11/11']);
            return $service->header_status(200);
        } catch (PDOException $e) {
            return $service->header_status_code(500, $e->getMessage());
        }
    }
    function login($data)
    {
        $service = new BaseService();

        $sql = 'SELECT * FROM users WHERE username =? ';
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$data->username]);
            if ($stmt->rowCount() > 0) {

                $user = $stmt->fetchAll();
                $password = $user[0]['password'];
                if (password_verify(md5($data->password), $password)) {
                    $token = $service->getCSRFToken();
                    $_SESSION['token_login'] = $token;
                    $_SESSION['loggedin'] = true;
                    $_SESSION['username'] = $data->username;
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return $service->header_status(500);
        }
    }

    function validate($data)
    {
        $service = new BaseService();
        if (!isset($data['username']) || !isset($data['password'])) {
            return  $service->header_status(400);
        }
    }
    public function xssafe($data, $encoding = 'UTF-8')
    {
        $service = new BaseService();
        try {
            $user = new UsersModel;
            foreach ($data as $key => $value) {
                $user->$key = htmlspecialchars($value, ENT_QUOTES | ENT_HTML401, $encoding);
            }
            return $user;
        } catch (\ErrorException $ex) {
            return $service->header_status(500);
        }
    }
}
