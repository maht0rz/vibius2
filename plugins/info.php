<?php

namespace vibius\plugins;

class info{

    public static function memtime(){
        return array('memory' => (memory_get_peak_usage(true) / 1024 / 1024),'time' => (round((microtime(true) - $GLOBALS['execution_time']) * 1000, 2)));
    }

}
