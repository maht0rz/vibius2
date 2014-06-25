<?php

session_start();
error_reporting(E_ALL); ini_set('display_errors', '1');

require'../vendor/autoload.php';
require '../app/settings/handlers.php';
require '../core/Framework.php';
require '../core/shutdown.php';


function myfunc($errno, $errstr, $errfile, $errline){
   $e = new vibius\core\Error();
   $er['file'] = $errfile;
   $er['line'] = $errline;
   $er['message'] = $errstr;
   $e->handle($er);
   die();
   return true;
}

set_error_handler("myfunc");
$framework->run();

