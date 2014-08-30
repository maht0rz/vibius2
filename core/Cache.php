<?php

namespace vibius\core;
use vibius\app\settings as settings;

/**
 * @author Matej Sima
 * @author matej.sima@gmail.com
 */
class Cache
{

    /**
     * @var string storing path to cache folder.
     */
    const PATH = '/app/cache/';


    /**
     * @param string $dir holding the base path to cache folder
     * @return integer Size of folder.
     */
    private function checkFolderSize($dir = self::PATH){
      $dir = dirname(__DIR__).$dir;
       $count_size = 0;
       $count = 0;
       $dir_array = scandir($dir);

       foreach($dir_array as $key=>$filename){
          if($filename!=".." && $filename!="."){
          
           if(is_dir($dir."/".$filename)){
           
            $new_foldersize = foldersize($dir."/".$filename);
            $count_size = $count_size + $new_foldersize;
           
            }else if(is_file($dir."/".$filename)){
              
              $count_size = $count_size + filesize($dir."/".$filename);
              $count++;
           }
          }
       }

     return $count_size;
    }


    /**
     * @param string @file path to file.
     * @return integer size of file from parameter.
     */
    public function checkFileSize($file){
        $lenght = mb_strlen($file);
        return $lenght;
    }

    /**
     * This method is used to check if cache file exists.
     *
     * @param string $name Name of the file.
     * @return boolean If file exists then returns true.
     */
    public function exists($name,$self = false){
         $dir = dirname(__DIR__).self::PATH;
         $files = scandir($dir);

          $re1='(\\d+)'; # Integer Number 1
          $re2='(\\^)'; # Any Single Character 1
          $re3='(\\d+)';    # Integer Number 2
          $re4='(\\^)'; # Any Single Character 2
          $re5='('.$name.')';   # Word 1
          $re6='(\\.)'; # Any Single Character 3
          $re7='(cache)'; # Word 2
          $unwanted = array('.','..');
          foreach ($files as $file) {
              if(!($file == '.')){
                if(!($file == '..')){

                  
                  $regex = preg_match_all("/".$re1.$re2.$re3.$re4.$re5.$re6.$re7."/is", $file, $matches);
                  
                  if($regex){
                      if($matches[5][0] == $name){
                        if($self){
                          unlink($dir.$file);
                        }else if(time() - $matches[3][0] <= $matches[1][0]){
                          return true;
                        }
                    }
                  }
                  
              }
            }
            
          }
          
    }


    /**
     * This method is used to delete cache file.
     *
     * @param string $name Name of the file to delete.
     * @return boolean If file delete was sucessfull.
     */
    public function delete($name){
         $dir = dirname(__DIR__).self::PATH;
         $files = scandir($dir);

          $re1='(\\d+)'; # Integer Number 1
          $re2='(\\^)'; # Any Single Character 1
          $re3='(\\d+)';    # Integer Number 2
          $re4='(\\^)'; # Any Single Character 2
          $re5='('.$name.')';   # Word 1
          $re6='(\\.)'; # Any Single Character 3
          $re7='(cache)'; # Word 2
          $unwanted = array('.','..');
          foreach ($files as $file) {
              if(!($file == '.')){
                if(!($file == '..')){
                  $regex = preg_match_all("/".$re1.$re2.$re3.$re4.$re5.$re6.$re7."/is", $file, $matches);
                    if($regex){
                        if(time() - $matches[3][0] <= $matches[1][0]){
                        return unlink($dir.$file);
                     }
                 }
              }
            }
            
          }
          
    }

    /**
     *  This method is used to create a cache file.
     *  @param integer $timeout Time until cache expires in seconds.
     *  @param string $name Name (identifier) of the cache file.
     *  @return boolean If cache creation was sucessfull.
     */
    public function create($timeout,$name){
        $size = $this->checkFolderSize() / 1024;
        if($size <= settings\config::$cacheFolderLimit){
          $file = dirname(__DIR__).self::PATH.$timeout.'^'.time().'^'.$name.'.cache';
          if($this->exists($name,true)){
            $this->delete($name);
          }
         
         $content = $this->checkFileSize(ob_get_contents());
         if(($content / 1024) < settings\Config::$cacheFileLimit){
            if(($size+$content / 1024) > settings\config::$cacheFolderLimit){
              return false;
            }
          $f = fopen($file,'w+');
            fwrite($f, ob_get_contents());
            fclose($f);
            return true;
         }
         

        }
    }


    /**
     *  This method is used to retrieve content of cache by name.  
     *
     *  @param string $name Name (identifier) of the cache file to load.
     *  @return string content of cache
     */
    public function get($name){
    $dir = dirname(__DIR__).self::PATH;
         $files = scandir($dir);

          $re1='(\\d+)'; # Integer Number 1
          $re2='(\\^)'; # Any Single Character 1
          $re3='(\\d+)';    # Integer Number 2
          $re4='(\\^)'; # Any Single Character 2
          $re5='('.$name.')';   # Word 1
          $re6='(\\.)'; # Any Single Character 3
          $re7='(cache)'; # Word 2
          $unwanted = array('.','..');
          foreach ($files as $file) {
              if(!($file == '.')){
                if(!($file == '..')){

                  
                  $regex = preg_match_all("/".$re1.$re2.$re3.$re4.$re5.$re6.$re7."/is", $file, $matches);
                  
                  if($regex){
                    if($matches[5][0] == $name){
                      
                      if(time() - $matches[3][0] <= $matches[1][0]){
                        return file_get_contents($dir.$file);
                      }
                  }
                  }
                  
              }
            }
            
          }
          
    }


    /**
     *  This method is used to create a cache file with content to be retrieved in form of reusable variables later.
     *  @param integer $timeout Time until cache expires in seconds.
     *  @param string $name Name (identifier) of the cache file.
     *  @param mixed $what Content to be cached.
     *  @return boolean If cache creation was sucessfull.
     */
    public function createVars($timeout,$name,$what){
        $size = $this->checkFolderSize() / 1024;
        echo '<br>folder:'.$size;
        if($size < settings\config::$cacheFolderLimit){
          $file = dirname(__DIR__).self::PATH.$timeout.'^'.time().'^'.$name.'.cache';
          if($this->exists($name,true)){
            $this->delete($name);
          }
         
         $content = $this->checkFileSize(serialize($what));
         echo '<br>content:'.$content / 1024;
         if(($content / 1024) < settings\Config::$cacheFileLimit){
            if(($size+$content / 1024) > settings\config::$cacheFolderLimit){
              return;
            }
          $f = fopen($file,'w+');
          fwrite($f, serialize($what));
          fclose($f);
         }
         
         

        }
    }


    /**
     *  This method is used to retrieve content of variable cache by name.  
     *
     *  @param string $name Name (identifier) of the cache file to load.
     *  @return mixed content of cache
     */
    public function getVars($name){
    $dir = dirname(__DIR__).self::PATH;
         $files = scandir($dir);

          $re1='(\\d+)'; # Integer Number 1
          $re2='(\\^)'; # Any Single Character 1
          $re3='(\\d+)';    # Integer Number 2
          $re4='(\\^)'; # Any Single Character 2
          $re5='('.$name.')';   # Word 1
          $re6='(\\.)'; # Any Single Character 3
          $re7='(cache)'; # Word 2
          $unwanted = array('.','..');
          foreach ($files as $file) {
              if(!($file == '.')){
                if(!($file == '..')){

                  
                  $regex = preg_match_all("/".$re1.$re2.$re3.$re4.$re5.$re6.$re7."/is", $file, $matches);
                  

                  if($regex){
                      if($matches[5][0] == $name){
                        if(time() - $matches[3][0] <= $matches[1][0]){
                          $content = file_get_contents($dir.$file);
                          
                          return unserialize($content);
                        }
                    }
                  }
                  
              }
            }
            
          }
          
    }



}
