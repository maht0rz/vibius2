<?php

namespace vibius\core;

use vibius\Error;
use vibius\Router;

class Framework{

    public function run(){

        try{
            ob_start();
            Router::run();
        }catch(\Exception $e){
            ob_clean();
            Error::handle($e);
        }

    }

}

$framework = new Framework();
