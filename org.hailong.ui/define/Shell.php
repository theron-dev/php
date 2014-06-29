<?php

class Shell{

	private $controllers;
	private $config;
	private $viewStateAdapter;
	private $view;
	private $viewController;
	
	public function __construct($config,$viewStateAdapter,$view,$viewController){
		$this->controllers = array();
		$this->config = $config;
		$this->viewStateAdapter = $viewStateAdapter;
		$this->view = $view;
		$this->viewController = $viewController;
	}
	
	public function length(){
		return count($this->controllers);
	}
	
	public function addController($controller){
		array_push($this->controllers,$controller);
	}
	
	public function getController($index){
		return isset($this->controllers[$index]) ? $this->controllers[$index] : null;
	}
	
	public function onLoadView(){
		foreach($this->controllers as $controller){
			$controller->onLoadView();
		}
	}
	
 	private static $current;
	
	public static function getCurrent(){
		return Shell::$current;
	}
	
	public static function staticRun($config,$viewStateAdapter,$view,$viewController){
		$shell = new Shell($config,$viewStateAdapter,$view,$viewController);
		$shell->run();
	}
	
	public function run(){
		
		$timestamp = doubleval( time()) + microtime();
		
		$config = $this->config;
		$viewStateAdapter = $this->viewStateAdapter;
		$view = $this->view;
		$viewController = $this->viewController;
		
		Shell::$current = $this;
		
		$inputData = array();
		
		foreach($_GET as $key => $value){
			$inputData[$key] = $value;
		}		
		foreach($_POST as $key => $value){
			$inputData[$key] = $value;
		}
		
		$data = input();
		
		if(is_array($data)){ 
			foreach($data as $key => $value){
				$inputData[$key] = $value;
			}
		}
		
		if($_FILES){
		
			$files = upload();
			if($files && is_array($files)){
				foreach($files as $name=>$value){
					$inputData[$name] = $value;
				}
			}
		}
		
		$context = new ServiceContext($inputData,$config);
	
		$viewContext = new RootViewContext();
		
		$viewLoaded = false;
		
		$isPostback = $_SERVER["REQUEST_METHOD"] == 'POST';
		
		$isMultipart = strpos($_SERVER["CONTENT_TYPE"],'multipart/form-data') !== false;
		
		$viewStateAdapter->setContext($context);

		$viewState = $viewStateAdapter->loadViewState();
		
		if($viewState){
			$viewContext->loadFromData($viewState);
			$viewLoaded = true;
		}

				
		pushViewContext($viewContext);
		
		$controller = new $viewController($context,$isPostback);

		if(!$viewLoaded){
			$this->onLoadView();
		}
		
		if($isPostback){
			
			$dataStr = $context->getInputDataValue("data-json");
			$data = json_decode($dataStr,true);

			if($data){
				try{
					$viewContext->updateSync($data);
				}
				catch(Exception $ex){
					output(array("error-code"=>$ex->getCode(),"error"=>$ex->getMessage()));
					return ;
				}
			}

			$action = $context->getInputDataValue("action");
			$target = $context->getInputDataValue("target");
			$target = $this->getController(intval($target));
			if($target && $action && method_exists($target, "on".$action)){
				$method = "on".$action;
				try{
					$target->$method();
				}
				catch(Exception $ex){
					output(array("error-code"=>$ex->getCode(),"error"=>$ex->getMessage()));
					return ;
				}
			}
		}
		
		$redrect = $viewContext->getRedirect();
		
		if($isPostback && !$isMultipart){
			if($redrect){
				$viewContext->pushAttribute("window.location", "href", $redrect);
			}
			output($viewContext->outputSyncData());
		}
		else if($redrect){
			header("Location: {$redrect}");
		}
		else {
			
			$name = $view;
			$ext = "";
			$index = strrpos($view, ".");
			if($index !== false){
				$name = substr($view, 0,$index);
				$ext = substr($view,$index);
			}
			
			$views = array();
			
			$userAgent = $_SERVER["HTTP_USER_AGENT"];
			
			if(strpos($userAgent, "iPhone") !== false || strpos($userAgent, "iPod") !== false){
				$views[] = $name."_iPhone".$ext;
			}
			
			if(strpos($userAgent, "iPad") !== false){
				$views[] = $name."_iPad".$ext;
			}
			
			if(strpos($userAgent,"WAP") !== false){
				$views[] = $name."_wap".$ext;
			}
		
			$views[] = $view;
			foreach($views as $v){
				if(file_exists($v)){
					require $v;
					break;
				}
			}
			
			echo "\n<script type='text/javascript'>";
			echo 'PostData=';
			echo json_encode($viewContext->outputData(),true);
			echo ';';
			echo '</script>';
			echo "\n<!--".(doubleval( time()) + microtime() - $timestamp)."-->";
		}
		
		$viewContext->finish();
	
		$viewStateAdapter->saveViewState($viewContext->getData());
		
		popViewContext($viewContext);
		
		Shell::$current = null;
	}
}
?>