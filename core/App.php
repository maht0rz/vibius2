<?php

namespace vibius\core;

class App extends \ArrayObject{

    public static $array = array();

    public function add($key,$what){
         self::$array[$key] = $what;
    }

    public function get($key){
        if(isset(self::$array[$key])){
            return self::$array[$key];
        }
    }
}