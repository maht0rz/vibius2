<?php

namespace vibius\core;

class Session{

  function __construct($index = 'vibius_session'){

      if(empty($_SESSION[$index])){
          $_SESSION[$index] = array();
      }
      if(empty($_SESSION[$index]['storage'])){
          $_SESSION[$index]['storage'] = array();
      }

      $this->index = $index;

  }

  function __destruct(){
    if(!empty($_SESSION[$this->index]['flash']) && !isset($_SESSION[$this->index]['have_flash'])){
          $_SESSION[$this->index]['have_flash'] = true;
    }else if(isset($_SESSION[$this->index]['have_flash'])){
          $_SESSION[$this->index]['flash'] = array();
          unset($_SESSION[$this->index]['have_flash']);
    }

    if(!empty($_SESSION[$this->index]['reflash'])){
      $_SESSION[$this->index]['have_flash'] = true;
      $_SESSION[$this->index]['flash'] = array();
      $_SESSION[$this->index]['flash'] = $_SESSION[$this->index]['reflash'];
      unset($_SESSION[$this->index]['reflash']);
    }
  }

  public function add($key,$value){
      $_SESSION[$this->index]['storage'][$key] = $value;
  }

  public function push($key,$value){
      array_push($_SESSION[$this->index]['storage'][$key], $value);
  }

  public function ifNotSetpush($key,$value){
      if(!in_array($value, $_SESSION[$this->index]['storage'][$key])){
         return array_push($_SESSION[$this->index]['storage'][$key], $value);
      }
  }

  public function createArray($in,$key){
      $_SESSION[$this->index]['storage'][$in][$key] = array();
  }

  public function getArray($in,$key){
      return $_SESSION[$this->index]['storage'][$in][$key];
  }

  public function existsArray($in,$key){
      if(isset($_SESSION[$this->index]['storage'][$in][$key])){
        return true;
      }
  }

  public function pushToArray($in,$key,$value){
      array_push($_SESSION[$this->index]['storage'][$in][$key], $value);
  }

  public function ifNotSetpushToArray($in,$key,$value){
      if(!in_array($value, $_SESSION[$this->index]['storage'][$in][$key])){
        return array_push($_SESSION[$this->index]['storage'][$in][$key], $value);
      }
  }

  public function get($key){
    if(isset($_SESSION[$this->index]['flash'][$key])){
      return $_SESSION[$this->index]['flash'][$key];
    }
    if(isset($_SESSION[$this->index]['storage'][$key])){
      return $_SESSION[$this->index]['storage'][$key];
    }
    throw new \Exception('Session offset not set ('.htmlentities($key).')');
  }

  public function remove($key){
    if(isset($_SESSION[$this->index]['flash'][$key])){
      unset($_SESSION[$this->index]['flash'][$key]);
      return;
    }
    if(isset($_SESSION[$this->index]['storage'][$key])){
      unset($_SESSION[$this->index]['storage'][$key]);
      return;
    }
    throw new \Exception('Session offset not set ('.htmlentities($key).')');
  }

  public function pull($key){
    if(isset($_SESSION[$this->index]['flash'][$key])){
      $return = $_SESSION[$this->index]['flash'][$key];
      unset($_SESSION[$this->index]['flash'][$key]);
      return $return;
    }
    if(isset($_SESSION[$this->index]['storage'][$key])){
      $return = $_SESSION[$this->index]['storage'][$key];
      unset($_SESSION[$this->index]['storage'][$key]);
      return $return;
    }
    throw new \Exception('Session offset not set ('.htmlentities($key).')');
  }

  public function exists($key){
    if(isset($_SESSION[$this->index]['flash'][$key])){
      return true;
    }
    if(isset($_SESSION[$this->index]['storage'][$key])){
      return true;
    }
  }

  public function getAll(){
    return array('storage' => $_SESSION[$this->index]['storage'], 'flash' => $_SESSION[$this->index]['flash']);
  }

  public function flush(){
    $_SESSION[$this->index]['storage'] = array();
    $_SESSION[$this->index]['reflash'] = array();
    $_SESSION[$this->index]['flash'] = array();
  }

  public function flash($key,$value){
    $_SESSION[$this->index]['flash'][$key] = $value;
  }

  public function flashArray($array){
    foreach($array as $key => $value){
      $_SESSION[$this->index]['flash'][$key] = $value;
    }
  }

  public function reflash($key = false){
    if(isset($_SESSION[$this->index]['flash'][$key]) && $key){
      $_SESSION[$this->index]['reflash'][$key] = $_SESSION[$this->index]['flash'][$key];
    }else if(!$key){
      $_SESSION[$this->index]['reflash'] = $_SESSION[$this->index]['flash'];
    }
  }

}
