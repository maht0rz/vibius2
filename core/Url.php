<?php
namespace vibius\core;

class Url{
    public function to($path){
         $exp = explode('public/index.php',$_SERVER['PHP_SELF']);
	    $exp = $exp[0]; 
        $url = 'http://'.$_SERVER['HTTP_HOST'].$exp.$path;
        return $url;
    }
}
