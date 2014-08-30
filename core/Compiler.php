<?php

namespace vibius\core;

class Compiler {

	public function less(){
		if(empty($this->less)){
			$this->less = new \lessc;
		}
		return $this->less;
	}

	public function scss(){
		if(empty($this->scss)){
			$this->scss = new \scssc;
		}
		return $this->scss;
	}
}
