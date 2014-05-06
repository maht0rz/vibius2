<?php

namespace vibius\core;






class Framework{


    public function run(){

        //turn off error reporting


        #$error = new Error();
        
        try{            //start output buffering
            ob_start();
            //start router to check for route-url match
            $app = new App();
                        $r = $app->get('Router');
            if(!empty($r)){
                require dirname(__DIR__).$r[0];
                $router = new $r[1]();
                $router->$r[2]($r[3]);
            }else{
                $router = new Router;
                $router -> run();    
            }
        }catch(\Exception $e){
            ob_clean();
                        $r = $app->get('Exception');
            if(!empty($r)){
                require dirname(__DIR__).$r[0];
                $router = new $r[1]();
                $router->$r[2]($e);
            }else{
                    $view = new View();
                    $view->load('errors/debug')->vars(array('message' => 'Exception: '.$e->getMessage(),'file' => $e->getFile(),'line' => $e->getLine()))->display();
                
            }
            
        }

    }

}
$framework = new Framework();