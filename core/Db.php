<?php

namespace vibius\core;
use vibius\app\settings as settings;
class Db{

    private static $db = null;

    public function connect(){
        if(!isset(self::$db)){
            self::$db = new \PDO(
                settings\config::$type . ":host=" . settings\config::$host . ";dbname=" . settings\config::$dbname . ";charset=utf8",
                settings\config::$username,
                settings\config::$password
            );
        }
        return self::$db;
    }
    
    public function model($name){
        require_once dirname(__DIR__).'/app/models/'.$name.'.php';
        
        return new $name;
    }

}
