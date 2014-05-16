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
            
                $error = new Error();
                $error->handle($e);
               
            
        }

    }

}
$framework = new Framework();