<?php


namespace App\Models;

use App\Classes\Database;

abstract class BaseModel {

    protected $db;

    function __construct(){
        $this->db = Database::connect()->database;
    }
    function count($table)
    {
        $dbh = $this->db->prepare("SELECT COUNT(*) FROM {$table}" );
        $dbh->execute();
        $count = $dbh->fetchColumn();
        return $count ;
    }
}