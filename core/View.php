<?php

namespace vibius\core;

class View{

    private $template = true;
    private static $rules = array();
    private static $GlobalVars = array();

    public function load($name){
        $this->file = dirname(__FILE__).'/../app/views/'.$name.'.tpl.php';
        if(!file_exists($this->file)){
            $this->template = false;
            $this->file = dirname(__FILE__).'/../app/views/'.$name.'.php';
            if(!file_exists($this->file)){
                throw new \Exception('View: <b>'.$this->file.'</b> not found');
            }
        }
        
        $this->args = array();
        return $this;
    }
    
    public function exists($name){
        $this->file = dirname(__FILE__).'/../app/views/'.$name.'.tpl.php';
        if(!file_exists($this->file)){
            $this->template = false;
            $this->file = dirname(__FILE__).'/../app/views/'.$name.'.php';
            if(!file_exists($this->file)){
               return false;
            }
        }
        return true;
    }
    
    
    public function vars($args = array()){
        foreach ($args as $key => $value) {
            $this->args[$key] = $value;
        }
        return $this;
    }

    private function parse($view){
          $re1='(\\{)'; # Any Single Character 2
          $re2='(\\{)'; # Any Single Character 2
          $re3='(\\$)'; # Any Single Character 3
          $re4='((?:[a-z][a-z0-9_]*))'; # Word 1
          $re5='(\\})'; # Any Single Character 5
          $re6='(\\})'; # Any Single Character 6     

                
                  if ($c=preg_match_all ("/".$re1.$re2.$re3.$re4.$re5.$re6."/is", $view, $matches))
                  {
                    foreach($matches[4] as $match){
                        $view = preg_replace("/".$re1.$re2.$re3.'('.$match.')'.$re5.$re6."/is","<?php echo $".$match.";?>",$view);
                    }
                  }

          $req='(\\{)'; # Any Single Character 1
          $ree='(\\{)'; # Any Single Character 2      
          $re1='.*?'; # Non-greedy match on filler
          $dlr='(\\$)'; # Any Single Character 3
          $re2='(\\[)'; # Any Single Character 1
          $re3='(\')';  # Any Single Character 2
          $re4='((?:[a-z][a-z0-9_]*))'; # Variable Name 1
          $re5='(\')';  # Any Single Character 3
          $re6='(\\])'; # Any Single Character 4
          $re7='(\\})'; # Any Single Character 5
          $re8='(\\})'; # Any Single Character 6    


                  if ($c=preg_match_all ("/".$req.$ree.$re1.$dlr.$re4.$re2.$re3.$re4.$re5.$re6.$re7.$re8."/is", $view, $matches))
                  {
                    foreach($matches[4] as $match){
                        foreach($matches[7] as $index){
                          $view = preg_replace("/".$req.$ree.$re1.$dlr.$re4.$re2.$re3.$re4.$re5.$re6.$re7.$re8."/is","<?php echo $".$match."['".$index."'];?>",$view);
                      }
                    }
                  }
        
         return $view;
    }

    private function parseRule($view){
          $re1='(\\{)'; # Any Single Character 1
          $re2='(\\{)'; # Any Single Character 2
          $re3='(\\$)'; # Any Single Character 3
          $re4='((?:[a-z][a-z]+))'; # Word 1
          $re5='(_)';   # Any Single Character 4
          $re6='((?:[a-z][a-z\\/:]+))'; # Word 2
          $re7='(\\})'; # Any Single Character 5
          $re8='(\\})'; # Any Single Character 6

          $results = array();
          $txt = preg_split("/".$re1.$re2.$re3."/is", $view);

            foreach ($txt as $value) {
               if ($c=preg_match_all ("/".$re4.$re5.$re6."/is", $value, $matches))
              {
                  $c1=$matches[1][0];
                  $c2=$matches[2][0];
                  $c3=$matches[3][0];
                 array_push($results, $c1.$c2.$c3);
                 # print "($c1) ($c2) ($c3) ($word1) ($c4) ($word2) ($c5) ($c6) \n";
              }
            }
           
            foreach ($results as $key) {
                $operators = explode('_',$key);
               
                foreach (self::$rules as $key => $value){
                   if($key == $operators[0]){
                   $replacer = $value($operators[1]);
                   $search = '{{$'.$key.'_'.$operators[1]."}}";
                   
                  $view = str_replace($search, $replacer, $view);
                    
                   }
                }
            }
            return $view;
    }

    public function addRule($key,$action){
        self::$rules[$key] = $action;
    }
    
    public function GlobalVar($key,$value){
        
        self::$GlobalVars[$key] = $value;
    }
    
    public function GlobalVarsArray($array){
        foreach($array as $key => $value){
            self::$GlobalVars[$key] = $value;   
        }
    }

    public function getRule($key){
        return self::$rules[$key];
    }
    
 public function getView(){
       foreach (self::$GlobalVars as $key => $value) {
           ${$key} = $value;
        }
        
        foreach ($this->args as $key => $value) {
            
            ${$key} = $value;
        }
        $view = file_get_contents($this->file);
            if($this->template){
                # echo "is tempalte!";
                $view = $this->parseRule($view);
                $view = $this->parse($view);
            }
    
       
        ob_start();
        eval('?> '.$view);
        $output = ob_get_contents();
        ob_clean();
        return $output;
    }

    public function display(){
        
        foreach (self::$GlobalVars as $key => $value) {
           ${$key} = $value;
        }
        
        foreach ($this->args as $key => $value) {
            
            ${$key} = $value;
        }
        $view = file_get_contents($this->file);
            if($this->template){
                # echo "is tempalte!";
                $view = $this->parseRule($view);
                $view = $this->parse($view);
            }
    
       
        eval('?> '.$view);
    } 
}
