<?php

namespace vibius\core;

use vibius\core\Container as Container;


class Facade{

     public static function __callStatic($name, $arguments)
    {
        $c = get_called_class();
        if(!Container::exists($c)){
          $class = $c::getContainerKey();
          $in = new $class();
          Container::add($c,$in);
        }

        $instance = Container::get($c);

        return call_user_func_array(array($instance,$name), $arguments);

    }


}
