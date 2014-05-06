<?php
use vibius\core as core;
use vibius\app\models as models;
class myController{

    public function __construct(){
        $this->view = new core\View();

    }

    public function test(){
        
        $cache = new core\Cache();
        
        $model = new models\myModel();
        $model->getById();
        
        
    }

    public function myFunctionGroup(){
    }

    public function myFunction($id){
        echo "CONTROLLING!";echo $id;
        $this->view->load('test');

        echo "<p>Memmory usage: ". (memory_get_peak_usage(true) / 1024 / 1024) ." MB";
        echo " | Execution time: ". (round((microtime(true) - $GLOBALS['execution_time']) * 1000, 2)) ." ms";
    }

}