<?php

namespace vibius\core;
use vibius\app\settings as settings;
use Illuminate\Database\Capsule\Manager as Capsule;

class Db extends Capsule{

    public function __construct(){

      if(!Container::exists('Capsule')){
       
          $capsule = new Capsule;
          $capsule->addConnection([
              'driver'    => settings\config::$type,
              'host'      => settings\config::$host,
              'database'  => settings\config::$dbname,
              'username'  => settings\config::$username,
              'password'  => settings\config::$password,
              'charset'   => 'utf8',
              'collation' => 'utf8_unicode_ci',
              'prefix'    => '',
          ]);

          // Make this Capsule instance available globally via static methods
          $capsule->setAsGlobal();

          // Setup the Eloquent ORM
          $capsule->bootEloquent();

          Container::add('Capsule',$capsule);

      }

      return Container::get('Capsule');
    }

}
