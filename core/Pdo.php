<?php

namespace vibius\core;
use vibius\app\settings as settings;

class Pdo extends \PDO{

    private static $db = null;

    public function __construct(){
            parent::__construct(
                settings\config::$type . ":host=" . settings\config::$host . ";dbname=" . settings\config::$dbname . ";charset=utf8",
                settings\config::$username,
                settings\config::$password
            );
    }

}