<?php

namespace vibius\core;
use vibius\app\settings as settings;

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
        $app = new App();
        $error = $app->get('error');
        $notfound = $app->get('404');
        
        if(is_object($e)){
            switch ($e->getCode()) {
                case 404:
                    if(!empty($notfound)){
                        require_once dirname(__DIR__).$notfound[0];
                        $class = new $notfound[1];
                        $class->$notfound[2]();
                        return;
                    }

                    break;

                default:
                    
                    break;
            }
            
        }
        
        if(!empty($error)){
                require_once dirname(__DIR__).$error[0];
                $class = new $error[1];
                $class->$error[2]();
                return;
                
            }else{
                
                    $this->display($e);
               
            }
        
        
        
    }
    
    public function shandle(){
        $error = error_get_last();
        if(!empty($error)){
            ob_clean();
            $app = new App();
            $r = $app->get('Error');
            if(!empty($r)){
                require_once dirname(__DIR__).$r[0];
                $router = new $r[1]();
                $router->$r[2]($r[3]);
            }else{
                $r = $app->get('404');
                if(!empty($r)){
                    var_dump($r);
                }else{
                        try{
                        $view = new View();
                        $view->load('errors/debug')->vars(array('message' => $error['message'],'file' => $error['file'],'line' => $error['line']))->display();

                    }catch(\Exception $e){
                                
                    }
                }
                
            }
            
        }
    }
}


