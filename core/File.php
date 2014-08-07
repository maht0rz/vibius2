<?php 

namespace vibius\core;

class File
{

    public function init($file,$path ='')
    {
    	$this->path = $path;
        $this->file = $file;
        $this->type = true;
        $this->maxSize = true;
        $this->error = false;

        return $this;
    }

    public function valid($file){
        if(preg_match('/^[a-zA-Z0-9]+\.[a-zA-Z]{3,4}$/', $file)) {
         return true;
        } else {
           return false;
        }
    }
    public function type($type)
    {
        foreach ($_FILES[$this->file]['name'] as $t) {

            $t = explode('.', $t);
            if (in_array($t[1], $type)) {

                $this->type = true;
            } else {
                $this->type = false;
                break;
            }
        }
        return $this;
    }

    public function getInfo()
    {
        return $_FILES[$this->file];
    }

    public function getSize()
    {
        $ar = array();
        foreach ($_FILES[$this->file]['size'] as $s) {
            array_push($ar, $s);
        }
        return ($ar);
    }

    public function size($size)
    {
        $this->size = $size;

        foreach ($_FILES[$this->file]['size'] as $s) {
            if (($s) <= $size) {
                $this->maxSize = true;
            } else {
                $this->maxSize = false;
                break;
            }
        }
        return $this;


    }

    public function status()
    {
        print_r($_FILES[$this->file]);
        print_r(get_object_vars($this));
        return $this;
    }

    public function names()
    {
        return $this->files;
    }

    public function error()
    {
        foreach ($_FILES[$this->file]['error'] as $e) {
            if ($e == 0) {
                $this->error = false;
            } else {
                $this->error = true;
                break;
            }
        }

        return $this;
    }

    public function randStr($l = 20)
    {

        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';

        for ($i = 0; $i < $l; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;

    }

    public function save($path ='', $name = false, $c = 5)
    {
        $this->files = array();
        $f = '';
        if (!empty($path)) {
            $f = $path . "/";
        }

        if(!empty($this->path)){
        	$f = $this->path.'/';
        }



        if ($this->type && $this->maxSize && !$this->error) {

            $num = count($_FILES[$this->file]['tmp_name']);

            for ($i = 0; $i < $num; $i++) {
                $fname = $_FILES[$this->file]['name'][$i];
                if (file_exists($f . $fname)) {
                    if ($name) {
                        while (file_exists($f . $fname)) {
                            $fname = $this->randStr($c) . "_" . $_FILES[$this->file]['name'][$i];
                        }
                        array_push($this->files, $fname);
                        move_uploaded_file($_FILES[$this->file]['tmp_name'][$i], $f . $fname);

                    } else {

                    }
                } else {
                    move_uploaded_file($_FILES[$this->file]['tmp_name'][$i], $f . $fname);
                    array_push($this->files, $fname);
                    echo $f . $fname;die();
                }
            }
            return $this->files;
        }

        return false;


    }


    public function setAnotherPath($path){
    	$this->apath = $path;
    }

    public function exists($name){
    	if(file_exists($name)){
    		return true;
    	}
        return false;
    }

    public function delete($name){
    	$p =  dirname(__DIR__).'/';
    	if(!empty($this->apath)){
    		$p .= $this->path;
    	}

    	$p .= $name;

    	if(file_exists($p)){
    		return unlink($p);
    	}

    	throw new \Exception('File '.$p.' does not exists');
    }

    public function copyFolder($name,$newname = ''){
    	if(empty($newname)){
    		$newname = $name;
    	}
        $name = dirname(__DIR__).'/'.$name;
        $newname = dirname(__DIR__).'/'.$newname;
    	exec("cp -r $name $newname");

    }

    public function copy($name,$newname = ''){
        if(empty($newname)){
            $newname = $name;
        }
        return copy(dirname(__DIR__).'/'.$name,dirname(__DIR__).'/'.$newname);

    }

    public function move($name,$newname){
    	return rename(dirname(__DIR__).'/'.$name,dirname(__DIR__).'/'.$newname);
    }

    public function append($file,$data){
    	return file_put_contents($file, $data, FILE_APPEND);
        
    }

    public function insert($file,$data){
        return file_put_contents($file, $data);
    }

    public function read($file){
        return file_get_contents($file);
    }

}