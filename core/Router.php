<?php
namespace vibius\core;

use vibius\app\hooks as hooks;

//router class 
class Router{

    const TYPE_ANY = 'any';
    const TYPE_GET = 'get';
    const TYPE_POST = 'post';
    const TYPE_AJAX = 'xmlhttprequest';

    const DEFAULT_INDEX_PATH = 'public/index.php';
    const DEFAULT_CONTROLLERS_PATH = '/app/controllers/';

    private static $routes = array();
    private static $groups = array();
    private static $params = array();
    private static $regex = array();
    private static $args = array();
    private static $spevars = array();

    //add route with no rules
    public function any($route,$action){
        $this->add(self::TYPE_ANY,$route,$action);
    }

    //add route which accepts get request only
    public function get($route,$action){
        $this->add(self::TYPE_GET,$route,$action);
    }

    //add route which accepts post request only
    public function post($route,$action){
        $this->add(self::TYPE_POST,$route,$action);
    }

    //add route which accepts ajax request only
    public function ajax($route,$action){
        $this->add(self::TYPE_AJAX,$route,$action);
    }

    //add route to arra
    public function add($type,$route,$action){
        self::$routes[$route] = array($type,$action);
    }
    
    public function group($route,$action){
        self::$groups[$route] = array($action);
    }

    public function dispatch(){
        //get basepath of app
		$this->base = explode(self::DEFAULT_INDEX_PATH,$_SERVER['PHP_SELF']);
		$this->base = $this->base[0];
		//cut basepath from request uri
		$this->url = $_SERVER['REQUEST_URI'];
		if($this->base != '/'){
			 $this->url = str_replace($this->base,'', $_SERVER['REQUEST_URI']);
			 
		}else{
			$this->url= substr($this->url,1);
		}
	       
		$this->url = explode('?',$this->url);
		$this->url = $this->url[0];
        //get request type
        $this->requestType = $_SERVER['REQUEST_METHOD'];
            self::$routes = array_reverse(self::$routes);
            //loop through all routes to find match
            foreach (self::$routes as $key => $options) {
                self::$spevars == array();
                
                $type = $options[0];
                //check if request type matches currently looped url
                if(strtoupper($type) == $this->requestType || $type == self::TYPE_ANY || (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $type == strtolower($_SERVER['HTTP_X_REQUESTED_WITH']))){
                    if($this->url == '' && $key == '/'){
                        //match found
                        
                        return array($key,$options[1],$type,self::$args);
                    }

                    if($this->url == $key){
                        //match found
                        
                        return array($key,$options[1],$type,self::$args);
                    }
                    
                    $segments = explode('/',$key);
                    self::$params = array();
                    self::$regex = array();
                    foreach ($segments as $s) {

                          $re1='(\\{)';    # Any Single Character 1
                          $re2='((?:[$][a-z][a-z]+))';
                          $re3='(\\})';    # Any Single Character 3
                          
                          $rVars = preg_replace ("/^".$re1.$re2.$re3."$/is", '((?:[a-z0-9-.@]*))', $s);
                          
                          $re1='((?:[a-z][a-z]+))';
                          $replaced = preg_replace ("/^".$re1."$/is", '('.$s.')', $rVars);
                            
                          
                            $re1='(\\{)';	# Any Single Character 1
                            $re2='(%)';	# Any Single Character 2
                            $re3='(.*)';	# Round Braces 1
                            $re4='(%)';	# Any Single Character 3
                            $re5='(\\})';	# Any Single Character 4
                            if($c=preg_match_all("/^".$re1.$re2.$re3.$re4.$re5."$/is", $replaced, $matches)){
                                $replaced = preg_replace("/^".$re1.$re2.preg_quote($matches[3][0]).$re4.$re5."$/is", $matches[3][0], $replaced);
                                
                                foreach($segments as $num => $val){
                                    if($val == $s){
                                        array_push(self::$spevars, $num);
                                    }
                                }
                                
                            }
                           
                            
                         
                           array_push(self::$regex,$replaced);

                

                      $reg = '';
                      $re1='(\\/)';
                      foreach (self::$regex as $rx) {
                        $reg = $reg.$rx.$re1;
                      }

                    }

                    $reg = substr_replace($reg ,"",-4);
                    if ($c=preg_match_all("/^".$reg."$/is", $this->url, $matches)){
                          if(count(explode('/',$this->url)) <= count($segments)){
                              $regex = '((?:[a-z0-9-.@]*))';
                              $url = explode('/',$this->url);
                              #var_dump($url);
                              $count=-1;
                              foreach (self::$regex as $key => $value) {
                                  $count++;
                                  if($value == $regex || in_array($count, self::$spevars)){
                                      array_push(self::$args,$url[$count]);
                                  }
                                  
                              }

                              //match found
                              
                              return array($key,$options[1],$type,self::$args);
                          }
                          
                      }
 
                }
            }
    }

    public function groupCheck(){
            #var_dump(self::$groups);
            self::$regex = array();
            foreach (self::$groups as $key => $options) {
                
                    $segments = explode('/',$key);
                    self::$params = array();
                    self::$regex = array();
                    foreach ($segments as $s) {

                          $re1='(\\{)';    # Any Single Character 1
                          $re2='((?:[$][a-z][a-z]+))';
                          $re3='(\\})';    # Any Single Character 3
                          
                           $rVars = preg_replace ("/".$re1.$re2.$re3."$/is", '((?:[a-z0-9-.@]*))', $s);
                          
                           

                          $re1='((?:[a-z][a-z]+))';
                          $replaced = preg_replace ("/".$re1."$/is", '('.$s.')', $rVars);
                         
                            $re1='(\\{)';	# Any Single Character 1
                            $re2='(%)';	# Any Single Character 2
                            $re3='(.*)';	# Round Braces 1
                            $re4='(%)';	# Any Single Character 3
                            $re5='(\\})';	# Any Single Character 4
                           
                            if($c=preg_match_all("/^".$re1.$re2.$re3.$re4.$re5."$/is", $replaced, $matches)){
                                $replaced = preg_replace("/^".$re1.$re2.preg_quote($matches[3][0]).$re4.$re5."$/is", $matches[3][0], $replaced);
                            }
                            
                           array_push(self::$regex,$replaced);

                

                      $reg = '';
                      $re1='(\\/)';
                      foreach (self::$regex as $rx) {
                        $reg = $reg.$rx.$re1;
                      }

                    }
            
            #$reg = substr_replace($reg ,"",-4);
            $regx = $reg.'((?:[a-z0-9-.@\\/]*))';
            $found = $options;
            if ($c=preg_match_all("/^".$regx."$/is", $this->url, $matches)){
                
                        if(is_callable($found[0])){
                        $hook = new hooks\preRouteExecution();
                        return call_user_func($found[0]);
                        }

                    $exp = explode('%',$found[0]);

                    if(!isset($exp[0]) || !isset($exp[1])){
                        throw new \Exception('Controller%method has wrong format');
                    }

                    $controller = $exp[0];
                    $method = $exp[1];

                    $file = dirname(__DIR__).self::DEFAULT_CONTROLLERS_PATH.$controller.'.php';

                    if(!file_exists($file)){
                        throw new \Exception('Controller file: <b>'.$file.'</b> does not exists');
                    }

                        require_once $file;

                    if(!class_exists($exp[0])){
                        throw new \Exception('Class: <b>'.$exp[0].'</b> of controller: <b>'.$file.'</b> does not exists');
                    }

                    $class = new $exp[0];

                    if(!method_exists($class,$exp[1])){
                        throw new \Exception('Method: <b>'.$exp[1].'</b> of controller: <b>'.$file.'</b> does not exists');
                    }

                    call_user_func_array(array($class,$exp[1]),array());
                }
                $reg = substr_replace($reg ,"",-4);
                if ($c=preg_match_all("/".$reg."$/is", $this->url, $matches)){
                    if(is_callable($found[0])){
                        $hook = new hooks\preRouteExecution();
                        return call_user_func($found[0]);
                        }

                    $exp = explode('%',$found[0]);

                    if(!isset($exp[0]) || !isset($exp[1])){
                        throw new \Exception('Controller%method has wrong format');
                    }

                    $controller = $exp[0];
                    $method = $exp[1];

                    $file = dirname(__DIR__).self::DEFAULT_CONTROLLERS_PATH.$controller.'.php';

                    if(!file_exists($file)){
                        throw new \Exception('Controller file: <b>'.$file.'</b> does not exists');
                    }

                        require_once $file;

                    if(!class_exists($exp[0])){
                        throw new \Exception('Class: <b>'.$exp[0].'</b> of controller: <b>'.$file.'</b> does not exists');
                    }

                    $class = new $exp[0];

                    if(!method_exists($class,$exp[1])){
                        throw new \Exception('Method: <b>'.$exp[1].'</b> of controller: <b>'.$file.'</b> does not exists');
                    }
                }
}
    }

    public function check($found){

        if(!$found){
            throw new \Exception('Route not found',404);
        }

            if(is_callable($found[1])){
            

                $hook = new hooks\preRouteExecution();
            $this->groupCheck();    
            return call_user_func_array($found[1],$found[3]);
            }

        $exp = explode('%',$found[1]);

            if(!isset($exp[0]) || !isset($exp[1])){
                throw new \Exception('Controller%method has wrong format');
            }

        $controller = $exp[0];
        $method = $exp[1];

        $file = dirname(__DIR__).self::DEFAULT_CONTROLLERS_PATH.$controller.'.php';

            if(!file_exists($file)){
                throw new \Exception('Controller file: <b>'.$file.'</b> does not exists');
            }

                require_once $file;

            if(!class_exists($exp[0])){
                throw new \Exception('Class: <b>'.$exp[0].'</b> of controller: <b>'.$file.'</b> does not exists');
            }

            $class = new $exp[0];

            if(!method_exists($class,$exp[1])){
                throw new \Exception('Method: <b>'.$exp[1].'</b> of controller: <b>'.$file.'</b> does not exists');
            }
            $this->groupCheck();
            call_user_func_array(array($class,$exp[1]), $found[3]);
            

    }
    //do some magic
    public function run(){
        $hook = new hooks\preRouting();
        require (dirname(__DIR__).'/app/routes.php');
        
        $this->check($this->dispatch());
    }

}
