<?php

namespace vibius\core;

use vibius\File;

class Input{

	/**
	 *	@param Identifier for $_GET
	 *	@return Data stored inside $_GET[@param];
	 */

	public function get($id){
			return $_GET[$id];
	}


	/**
	 *	@param Identifier for $_POST
	 *	@return Data stored inside $_POST[@param];
	 */

	public function post($id){
			return $_POST[$id];
	}


	/**
	 *	@throws Exception, if index does not exists.
	 *	@param Identifier for $_GET
	 *	@return Data stored inside $_GET[@param], if index does not exists.
	 */

	public function getIfExists($id){
		if(isset($_GET[$id])){
			return $_GET[$id];
		}
		throw new \Exception('$_GET['.$id.'] does not exists');
	}


	/**
	 *	@throws Exception, if index does not exists.
	 *	@param Identifier for $_POST
	 *	@return Data stored inside $_POST[@param], if index is valid.
	 */

	public function postIfExists($id){
		if(isset($_POST[$id])){
			return $_POST[$id];
		}
		throw new \Exception('$_POST['.$id.'] does not exists');
	}


	/**
	 *	@throws Exception, if index does not exists.
	 *	@param Identifier for $_POST
	 *	@return Data stored inside $_POST[@param], if index is valid.
	 */

	public function file($id){
		if(!empty($this->path)){
			return File::init($id,$this->path);
		}else{
			return File::init($id);
		}
		
	}

	public function setSavePath($path){
    	$this->path = $path;
    	return $this;
    }

}