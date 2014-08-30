<?php
use vibius\Error;
function shutdown(){
    $error = error_get_last();
    if(!empty($error)){
        Error::handle($error);
    }
}
register_shutdown_function('shutdown');
