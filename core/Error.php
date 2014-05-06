<?php

namespace vibius\core;

class Error{
    public function handle(){
        $error = error_get_last();
        if(!empty($error)){
            ob_clean();
            $app = new App();
                        $r = $app->get('Error');
            if(!empty($r)){
                require dirname(__DIR__).$r[0];
                $router = new $r[1]();
                $router->$r[2]($r[3]);
            }else{
                try{
                    
                    $view = new View();
                    $view->load('errors/debug')->vars(array('message' => $error['message'],'file' => $error['file'],'line' => $error['line']))->display();
                    
                }catch(\Exception $e){
                    echo $e->getMessage();            
                }
                
            }
            
        }
    }
}


