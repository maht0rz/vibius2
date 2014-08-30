<?php

namespace vibius\core;

/**
 * @author Matej Sima
 * @author matej.sima@gmail.com
 */
class AssetsBootstraper{

    /**
     * @var array $assets Holds array of all bootstraper asset collections.
     */
    private static $assets = array();


    function __construct() {
        $this->path = dirname(__DIR__).'/public/';
        $this->_url = new \vibius\core\Url();
    }


    /**
     * Used to set base path for requiring assets via bootstraper.
     *
     * @param string $path Sets base path for assets bootstraper.
     */
    public function setPath($path){
        $this->path = dirname(__DIR__).$path;
    }


    /**
     * This method adds collection of assets to instance of AssetsBootstraper class.
     *
     * @param string $name Sets name of assets collection to be added.
     * @param array[] $assets Contains names of assets to be added to collection.
     * @throws \Exception if assets in collection array are wrongly formated.
     */
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


    /**
     * Use this method to retrieve stylesheet from an assets collection.
     *
     * @param string $name Name for collection to retrieve stylesheets from.
     * @throws \Exception if $name was not found in array of collections.
     */
    public function getStylesheet($name){
        header('Content-type: text/css');
        $baseUrl = $this->_url->to('');
        if(!isset(self::$assets[$name])){
            throw new \Exception('assets colletion not found');
        }
        foreach(self::$assets[$name] as $asset){
            if($asset[1] == 'css'){
                $file = $this->path.$asset[0].'.css';
                if(file_exists($file)){
                    require_once $file;
                }
            }

        }

    }


    /**
     * Use this method to retrieve javascript from an assets collection.
     *
     * @param string $name Name for collection to retrieve javascript files from.
     * @throws \Exception if $name was not found in array of collections..
     */
    public function getJavaScript($name){
        header('Content-type: text/javascript');
        $baseUrl = $this->_url->to('');
        if(!isset(self::$assets[$name])){
            throw new \Exception('assets colletion not found');
        }
        foreach(self::$assets[$name] as $asset){
            if($asset[1] == 'js'){
                $file = $this->path.$asset[0].'.js';
                if(file_exists($file)){
                    require_once $file;
                }
            }
        }

    }





}
