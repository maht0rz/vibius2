<?php


class customRouter{
    public function initmethod($params){
        
        require dirname(__FILE__).'/../../routes.php';
    }

    public static function add($route){
        echo $route;
    }

}