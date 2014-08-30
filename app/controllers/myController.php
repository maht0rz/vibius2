<?php

use vibius\Session;

class myController{


    public function test(){

        Session::add('test','lol');
        echo 'shiez';
    }

}
