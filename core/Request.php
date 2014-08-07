<?php

namespace vibius\core;
use vibius\Container;

class Request {

	public function segment($num){
		$segments = explode('/',Container::get('request.url'));
		return $segments[$num];
	}

}
