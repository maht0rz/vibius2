<?php

namespace vibius\core;
use vibius\app\settings as settings;
use vibius\core\Container;

class Error{

    public function display($e){
         if(is_object($e)){
                $file = $e->getFile();
                $wline = $e->getLine();
                $message = $e->getMessage();
         }else{
             $file = $e['file'];
             $wline = $e['line'];
             $message = $e['message'];

         }
        require 'debugger.php';
    }

    public function handle($e){
        ob_clean();
        if(is_object($e)){
            switch ($e->getCode()) {
                case 404:
                    if(Container::exists('404')){
                        $action = Container::get('404');
                        $action();
                        return;
                    }
                    break;

                default:

                    break;
            }

        }

            if(Container::exists('error')){
                $action = Container::get('error');
                $action($e);
                return;
            }else{
                    $this->display($e);
            }
    }

}
