<?php
use vibius\core as core;

$route = new core\Router();
$view = new core\View();


$route->get('',function() use($view){
    $view->load('welcome')->display();
});