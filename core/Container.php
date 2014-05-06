<?php

namespace vibius\core;

class Container{

    public static $array = array();

    public function add($key,$what){
        return self::$array[$key] = $what;
    }

    public function get($key){
        if(isset(self::$array[$key])){
            return self::$array[$key];
        }
    }
}