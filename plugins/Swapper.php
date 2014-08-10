<?php

namespace vibius\plugins;

use vibius\Session;
use vibius\View;
use vibius\Container;

//Container::add('vibius\Session',new \vibius\core\Session('swapper'));


class Swapper{

	/**
	 * @var JSON with final response
	 */

	private $response = array();

	/**
	 * @var Array holding route blocks
	 */

	private $blocks;

	/**
	 * @var JSON holding request data
	 */

	private $responseBlocks;

	/**
	 * @var Boolean determining if request is made with Swapper.js 
	 */

	private $swapperRequest;

	/**
	 * @var JSON holding old request data
	 */

	private $oldRequest;

	
	/**
	 *  @param Array with blocks for route
	 */

	public function setBlocks($blocks){
		$this->blocks = $blocks;
	}

	/**
	 * 
	 */

	public function addLayout($name,$layout){
		$this->layouts[$name] = $layout;
	}

	public function getLayout($name){
		return $this->layouts[$name];
	}

	/**
	 * 
	 */

	public function setName($name){
		$this->blockName = $name;
	}

	/**
	 *  Determine if request comes from swapper.js or if it's a brand new HTTP request
	 */

	public function getRequest(){
		
		if(isset($_GET['swapper_request']) || isset($_POST['swapper_request'])){

			 $this->swapperRequest = true;
		}else{
			$this->swapperRequest = false;
		}

		if(Session::exists('swapper.oldBlockName')){
			$this->oldBlockName = Session::get('swapper.oldBlockName');
		}else{
			$this->oldBlockName = '';
		}

		return $this->swapperRequest;
	}

	/**
	 *  Calculate response data from current request needs, and data of previous request.
	 */

	private function parseRequest(){
		
		if($this->swapperRequest){
			if($this->blockName != $this->oldBlockName){
				header('Content-Type: application/json');
				$link = explode('?',$_SERVER['REQUEST_URI']);
				$output = array('swapper_newpage' => true, 'swapper_newpage_link' => 'http://'.$_SERVER['HTTP_HOST'].$link[0]);
				echo json_encode($output);
				die();
			}
			$this->oldRequest = array();
			if(Session::exists('swapper.oldRequest')){
				$this->oldRequest = Session::get('swapper.oldRequest');
			}

			$this->responseBlocks = array();
			/*echo "old:";
			print_r($this->oldRequest);*/
			foreach($this->blocks as $block => $vars){
					#echo $block;
					if(!isset($this->oldRequest[$block])){

						$this->responseBlocks[$block] = $vars;

					}else{
						$diff = $this->arrayRecursiveDiff($vars,$this->oldRequest[$block]);
						$this->diff = $diff;
						if(!empty($diff)){
							$this->responseBlocks[$block] = $vars;
						}
					}
			}
			/*echo "New response:";
			print_r($this->responseBlocks);
			die();*/
			return;
		}

		$this->responseBlocks = $this->blocks;
	}


	public function arrayRecursiveDiff($aArray1, $aArray2) { 
	    $aReturn = array(); 

	    foreach ($aArray1 as $mKey => $mValue) { 
	        if (array_key_exists($mKey, $aArray2)) { 
	            if (is_array($mValue)) { 
	                $aRecursiveDiff = $this->arrayRecursiveDiff($mValue, $aArray2[$mKey]); 
	                if (count($aRecursiveDiff)) { $aReturn[$mKey] = $aRecursiveDiff; } 
	            } else { 
	                if ($mValue != $aArray2[$mKey]) { 
	                    $aReturn[$mKey] = $mValue; 
	                } 
	            } 
	        } else { 
	            $aReturn[$mKey] = $mValue; 
	        } 
	    } 


	    return $aReturn; 
	} 


	/**
	 * 
	 */

	private function buildResponse(){
		
		$output = '';

		if(!$this->swapperRequest){
			/*echo "<pre>";
			print_r($this->responseBlocks);
			die();*/
			foreach($this->responseBlocks as $block => $vars){

					if(!isset($vars['vars'])){
						$vars['vars'] = array();
					}
					if(!empty($vars['views'])){
						
						
						if(is_array($vars['views'])){
							foreach($vars['views'] as $view){
								if(View::exists($view)){
									$output .= View::load($view)->vars($vars['vars'])->getView();
								}
							}
						}else{
							$output .= View::load($vars['views'])->vars($vars['vars'])->getView();
						}
					}
					if(!empty($vars['contents'])){
						if(!empty($output)){
							$output .= $vars['contents'];
						}else{
							$output = $vars;
						}
					}
					
			}

			return $output;
		}

		header('Content-Type: application/json');
			$output = array();
			foreach($this->responseBlocks as $block => $vars){
				#print_r($vars);
					if(!isset($vars['vars'])){
						$vars['vars'] = array();
					}

					if(!empty($vars['views'])){
						if(is_array($vars['views'])){
							foreach($vars['views'] as $view){
								if(empty($output[$block])){
									$output[$block] ='';
								}
								$output[$block] .= View::load($view)->vars($vars['vars'])->getView();
							}
							
						}else{
							$output[$block] = View::load($vars['views'])->vars($vars['vars'])->getView();
						}
					}

					if(!empty($vars['contents'])){
						if(!empty($output[$block])){
							$output[$block] .= $vars['contents'];
						}else{
							$output[$block] = $vars['contents'];
						}
					}
					
			}		
		return json_encode($output);

	}

	/**
	 * 
	 */

	public function error($error){
		$this->error = $error;
	}

	/**
	 * 
	 */

	public function isSwapperRequest(){
		return $this->getRequest();
	}

	/**
	 * 
	 */

	public function sendError($error){
		header('Content-Type: application/json');
		if(is_object($error)){
			$error = $error->getMessage();
		}else if(is_array($error)){
			$error = $error['message'];
		}
		echo json_encode(array('swapper_error' => true,'swapper_error_message' => $error));
	}

	/**
	 *  	
	 *  @return JSON with new page data
	 */

	public function respond(){

		$this->getRequest();
		$this->parseRequest();
		Session::add('swapper.oldRequest',$this->blocks);

		Session::add('swapper.oldBlockName',$this->blockName);
		/*if(!empty($this->diff)){
			echo "<pre>";print_r($this->diff);die();
		}*/
		echo $this->buildResponse();
		
	}

}
