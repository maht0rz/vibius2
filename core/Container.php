<?php

namespace vibius\core;

class Container {

	public static $array = array();

	public static function add($key, $what) {
		return self::$array[$key] = $what;
	}

	public static function get($key) {
		if (isset(self::$array[$key])) {
			return self::$array[$key];
		}
		throw new \Exception('Key '.$key.' of container not found');
	}

	public static function exists($key){
		if(isset(self::$array[$key])){
			return true;
		}
		return false;
	}

	public static function fire($key, $params = array()) {
		if (isset(self::$array[$key])) {
			$func = self::$array[$key];
			if (is_callable($func)) {
				return call_user_func_array($func, $params);
			}
			throw new \Exception('Key '.$key.' of container is not a function');
		}

		throw new \Exception('Key '.$key.' of container not found');
	}
}
