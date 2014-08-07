<?php

namespace vibius\core;

class Lang{
    
    
    function __construct($path = '/../app/lang'){
        $this->path = dirname(__FILE__).$path.'/';
    }
    public function setPath($path){
        $this->path = dirname(__FILE__).$path.'/';
    }
    
    public function setFile($file){
        $this->file = $file;
    }
    
    public function getFast($key, $file = ''){
        
    }
    
    public function get($key,$file = ''){
       if(isset($this->file)){
           $file = $this->file;
       }
       if(file_exists($this->path.$file.'.php')){
           if(!isset($this->{'f_'.$file})){
               
                $f = require $this->path.$file.'.php';
                $this->{'f_'.$file} = $f;
           }else{
               $f = $this->{'f_'.$file};
           }
       
            if(isset($f[$key])){
                return $f[$key];
            }
       }
       
       return false;
    }
    
    public function getAll($file = ''){
       if(isset($this->file)){
           $file = $this->file;
       }
       if(file_exists($this->path.$file.'.php')){
           if(!isset($this->{'f_'.$file})){
               
                $f = require $this->path.$file.'.php';
                $this->{'f_'.$file} = $f;
           }else{
               $f = $this->{'f_'.$file};
           }
           return $f;
       }
       
       return false;
    }
    
    public function add($vals,$overwrite=false,$file = ''){
       if(isset($this->file)){
           $file = $this->file;
       }
       if(file_exists($this->path.$file.'.php')){
           if(!isset($this->{'f_'.$file})){
               
                $f = require $this->path.$file.'.php';
                $this->{'f_'.$file} = $f;
           }else{
               $f = $this->{'f_'.$file};
           }
           if(!is_array($f)){
               $f = array();
           }
           foreach($vals as $key => $value){
               if(!$overwrite){
                   if(!isset($f[$key])){
                        $f[$key] = $value;
                    }   
               }else{
                   $f[$key] = $value;
               }   
           }
           
          return file_put_contents($this->path.$file.'.php', "<?php return ".  var_export($f,true).";");
           
       }
    }
    
    public function remove($keys,$file = ''){
       if(isset($this->file)){
           $file = $this->file;
       }
       if(file_exists($this->path.$file.'.php')){
           if(!isset($this->{'f_'.$file})){
               
                $f = require $this->path.$file.'.php';
                $this->{'f_'.$file} = $f;
           }else{
               $f = $this->{'f_'.$file};
           }
           
           foreach ($keys as $key){
               if(isset($f[$key])){
                   unset($f[$key]);
               }
           }
           
           
          return file_put_contents($this->path.$file.'.php', "<?php return ".  var_export($f,true).";");
           
       }
    }
    
    public function exists($file='',$selector = ''){
       if(isset($this->file)){
           $file = $this->file;
       }
       if(file_exists($this->path.$file.'.php')){
           if(!empty($selector)){
               if(!isset($this->{'f_'.$file})){
               
                    $f = require $this->path.$file.'.php';
                    $this->{'f_'.$file} = $f;
               }else{
                   $f = $this->{'f_'.$file};
               }
               if(empty($f[$selector])){
                   return true;
               }
               return false;
           }
           return true;
       }
    }
    
    public function delete($file=''){
       if(isset($this->file)){
           $file = $this->file;
       }
       if(file_exists($this->path.$file.'.php')){
           unlink($this->path.$file.'.php');
       }
    }
    
    public function create($file=''){
       if(isset($this->file)){
           $file = $this->file;
       }
       if(!file_exists($this->path.$file.'.php')){
           $handle = fopen($this->path.$file.'.php', 'w+');
           if($handle){
             return true;  
           }
       }
    }
}
