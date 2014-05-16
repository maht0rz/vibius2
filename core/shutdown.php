<?php
function shutdown(){
    $error = error_get_last();
    if(!empty($error)){
        
        $c = new vibius\core\Error();
        $c->handle($error);
    }
}
register_shutdown_function('shutdown');