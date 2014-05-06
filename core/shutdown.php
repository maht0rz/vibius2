<?php
function shutdown(){

                $c = new vibius\core\Error();
                $c->handle();    
            
     
}
register_shutdown_function('shutdown');