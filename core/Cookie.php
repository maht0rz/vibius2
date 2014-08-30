<?php

namespace vibius\core;

class Cookie {
 
        public function set($key, $value, $expire) {

                if(!empty($expire) && !empty($key) && !empty($value)) {
                        
                        setcookie( $key, $value, time()+$expire );
 
                        if(isset($_COOKIE[$key])) {
                                return true;
                        }
                }
        }
 
 
        public function get($name) {
 
                if(!empty($name)) {
 
                        return ( isset( $_COOKIE[ $name ] ) ) ? $_COOKIE[ $name ] : '';
               
                }
        }
 
 
        public function exists($name) {
                if(isset( $_COOKIE[$name] )) {
                        return true;
                }else {
                        return false;
                }
        }
 
        public function remove($name) {
                if( isset( $_COOKIE[$name]) ) {
 
                        unset( $_COOKIE[$name]);
 
                }
        }
 
}