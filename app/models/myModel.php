<?php

namespace vibius\app\models;
use vibius\core as core;

class myModel{

    public function getById(){
        echo "getting data by id";
        $db = new core\Db();
        $pdo = $db->connect();
        var_dump($pdo);
    }

}