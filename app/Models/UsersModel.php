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

        $sql = "INSERT INTO users (username, password, email, phone, create_at ) VALUES (?, ?, ?, ?, ?)";
        try {
            $stmt = $this->db->prepare($sql);
            $password = password_hash(md5($data->password), PASSWORD_DEFAULT);

            $stmt->execute([$data->username,$password ,$data->username,$data->username,'2021/11/11']);
            return $service->header_status(200);
        } catch (PDOException $e) {
            return $service->header_status(500);

        }
    }
    function login($data){
        $service = new BaseService();

        $sql = "SELECT * FROM  WHERE username =? and password=?";
        try {
            $stmt = $this->db->prepare($sql);
            $password = password_hash( md5($data->password), PASSWORD_DEFAULT);
            $stmt->execute([$data->username,$password ]);
            if( $stmt->rowCount()>0)
                return true;
            else
                return false;
        } catch (PDOException $e) {
            return $service->header_status(500);

        }
    }

    function validate($data)
    {
        $service = new BaseService();
        if (!isset($data['username']) || !isset($data['password']) ) {
            return  $service->header_status(400);
        }
    }
   
}
