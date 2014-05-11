<?php

namespace vibius\plugins;
/**
 * Validation-Handler is part of VibiusPHP.
 * Author: Aaqib Anees
 * Date: 8.4.2014
 * Time: 9:15 AM
 */
 
 
 
/*
*       Validate an All Forms
*       Need to Create an Object $validate = new Validate();
*       Defined Your validations as an array()
*       $valid = $validate->get($_GET / $_POST, [
 
                'textfieldname' => [
                       
                        'min'           =>              5,
                        'max'           =>              30,
                        'required'      =>              true|false,
                        'email' => 'true|false',
                        'password' => 'strenght:1|2|3'
 
                ]
        ]);
 
        check if all validation passed like this
        if($valid->isValid()) {
 
                        passed;
        }
 
                print_r($valid->errors());
 
        and also echo an specific field error echo $valid->errors('fieldname');
        password:
            1 => number and charater needed
            2 => number and uppercase character needed
            3 => number, uppercase character and special symbol needed (!@#$%^&*)
*/
class Validate {
 
        /**
    * @var Bool The content of Validate. Validation Passed true or false.
    */
        private $_passed = false;
 
 
        /**
    * @var array The contents of Validate. Hold All Errors.
    */
        private $_errors;
 
 
 
        /**
    * Adds an Validations to each Input.
    * @param $source for Method type : POST/GET.
    * @param $items for validations Array().
    */
    
        public function exists($inputs){
                foreach ($inputs as $input) {
                $a = explode(':',$input);
                if($a[0] == 'POST' || $a[0] == 'post'){
                    if(empty($_POST[$a[1]])){
                        return false;
                    }
                }else{
                    if(empty($_GET[$a[1]])){
                        return false;
                    }
                }
            }
            return true;
        }
    
        public function get($source , $items = array()) {
 
                foreach( $items as $input => $rules ) {
                        foreach( $rules as $rule => $rule_value ) {
 
                                $value = $source[$input];

                                if( $rule === 'required' || $rule === 'Required' && empty($value) ) {
                                                $this->PushErr($input ,"{$input} is {$rule}");
 
                                }elseif ( !empty($value) ) {
                                       
                                        switch ($rule) {
                                                case 'min':
                                                        if(strlen($value) < $rule_value){
                                                                $this->PushErr($input , "{$input} Must be Minimum of {$rule_value} Characters");
                                                        }
                                                        break;
                                               
                                                case 'max':
                                                        if(strlen($value) > $rule_value){
                                                                $this->PushErr($input , "{$input} Must be Maximum of {$rule_value} Characters");
                                                        }
                                                        break;
 
 
                                                case 'matches':
                                                        if($value !== $source[$rule_value]){
                                                                $this->PushErr($input, "{$rule_value} do not match");
                                                        }
                                                        break;
 
                                                case 'email':
                                                        if($rule_value){
                                                                $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/'; 
                                                                if(!preg_match($regex, $value)){
                                                                    $this->PushErr($input, "{$rule_value} is not an email");
                                                                }
                                                        }
                                                        break;
                                                case 'password':
                                                        $rules = explode('|',$rule_value);
                                                        foreach ($rules as $rule) {
                                                            $rule = explode(':',$rule);
                                                            switch ($rule[0]) {
                                                                case 'strength':
                                                                        switch($rule[1]){
                                                                            case '1':
                                                                                if(preg_match("#[0-9]+#", $value) && preg_match("#[a-z]+#", $value) ){
                                                                                    
                                                                                }else{
                                                                                    $this->PushErr($input, "{$rule_value} is not valid password");
                                                                                }
                                                                                break;
                                                                            case '2':
                                                                                if(preg_match("#[0-9]+#", $value) && preg_match("#[a-z]+#", $value) && preg_match("#[A-Z]+#", $value)){
                                                                                }else{

                                                                                    $this->PushErr($input, "{$rule_value} is not valid password");
                                                                                }
                                                                                break;
                                                                            case '3':
                                                                                if(preg_match("#[0-9]+#", $value) && preg_match("#[a-z]+#", $value) && preg_match("#[A-Z]+#", $value) && preg_match("#\W+#", $value) ){
                                                                                }else{

                                                                                    $this->PushErr($input, "{$rule_value} is not valid password");
                                                                                }
                                                                                break;
                                                                        }
                                                                    break;
                                                            }
                                                        }
                                                        break;
                                        }
 
 
                                }
 
 
                        }
 
                }
 
                if( empty($this->_errors) ) {
 
                        $this->_passed = true;
                }
 
                return $this;
        }
 
 
 
        /**
    * Adds an Errors to each Input with input name.
    * @param $key for Input Name.
    * @param $error for error.
    */
        private function PushErr($key , $error) {
                $this->_errors[$key] = $error;
        }
 
 
        /**
    * Get Error With the Field name or All.
    * @param $key for Input Name.
    */
        public function errors($catch = null) {
                if($catch != '') {
 
                        if (array_key_exists($catch, $this->_errors)) {
                               
                                return $this->_errors[$catch];
 
                        }else {
 
                                return '';
 
                        }
 
                       
 
                }else {
 
                        return $this->_errors;
                }
       
                return '';
        }
 
        /**
    * This Function Will Return True If Validation Passed Otherwise return false.
    */
        public function isValid() {
                return $this->_passed;
        }
 
}