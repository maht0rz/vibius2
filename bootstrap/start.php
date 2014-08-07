<?php


session_start();
error_reporting(E_ALL); ini_set('display_errors', '1');

require'../vendor/autoload.php';
require '../app/settings/settings.php';
require '../core/Framework.php';
require '../core/shutdown.php';

use vibius\Error;

function handle($errno, $errstr, $errfile, $errline){

   $er['file'] = $errfile;
   $er['line'] = $errline;
   $er['message'] = $errstr;
   Error::handle($er);
   die();
}

set_error_handler("handle");
$framework->run();
