<?php
namespace vibius\core;

class Url{
    public function to($path){
        $url = 'http://'.$_SERVER['HTTP_HOST'].explode('public/index.php',$_SERVER['PHP_SELF'])[0].$path;
        return $url;
    }
}