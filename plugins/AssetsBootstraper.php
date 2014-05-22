<?php

namespace vibius\plugins;

class AssetsBootstraper{
    
    private static $assets = array();
    
    function __construct() {
        $this->path = dirname(__DIR__).'/public/';
    }
    
    public function setPath($path){
        $this->path = dirname(__DIR__).$path;
    }
    
    public function addCollection($name,$assets){
        self::$assets[$name] = array();
        foreach($assets as $asset){
            $a = explode(':',$asset);
            if(isset($a[0]) && isset($a[1])){
                array_push(self::$assets[$name], array($a[0],$a[1]));
                
            }else{
                throw new \Exception('wrong assets format');
            }
        }
    }
    
    public function getStylesheet($name){
        if(!isset(self::$assets[$name])){
            throw new \Exception('assets colletion not found');
        }
        foreach(self::$assets[$name] as $asset){
            if($asset[1] == 'css'){
                $file = $this->path.$asset[0].'.css';
                if(file_exists($file)){
                    require $file;
                }
            }
        }
        
    }
    
    public function getJavaScript($name){
        if(!isset(self::$assets[$name])){
            throw new \Exception('assets colletion not found');
        }
        foreach(self::$assets[$name] as $asset){
            if($asset[1] == 'js'){
                $file = $this->path.$asset[0].'.js';
                if(file_exists($file)){
                    require $file;
                }
            }
        }
        
    }
    
    
    
    
    
}
