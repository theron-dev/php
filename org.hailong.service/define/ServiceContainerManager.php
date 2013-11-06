<?php

class ServiceContainer implements IServiceContainer{
	private $serviceClass;
	private $instance;
	private $taskTypes;
	private $config;
	private $context;
	private $security;
	
	public function ServiceContainer($serviceClass){
		if(class_exists($serviceClass)){
			$this->serviceClass = $serviceClass;
			$this->instance = null;
			$this->taskTypes = array();
		}
		else{
			throw new ServiceException("Not found service class $serviceClass", SERVICE_ERROR_NOT_FOUND_SERVICE_CLASS);
		}
	}
	
	public function setContext($context){
		$this->context = $context;
		if($this->instance){
			$this->instance->setContext($context);
		}
	}
	
	
	/**
	*
	* @param Class $taskType
	*/
	public function addTaskType($taskType){
		if(class_exists($taskType)){
			$this->taskTypes[$taskType] = true;
		}
		else{
			throw new ServiceException("Not found task class $taskType", SERVICE_ERROR_NOT_FOUND_TASK_CLASS);
		}
	}

	
	/**
	*
	* @param Class $taskType
	*/
	public function removeTaskType($taskType){
		$this->taskTypes[$taskType] = false;
	}
	
	/**
	*
	* @return Boolean
	*/
	public function isInstance(){
		return $this->instance != null;
	}

	/**
	* Create IService Instance
	*/
	public function createInstance(){
		if($this->instance == null){
			$this->instance = new $this->serviceClass();
			$this->instance->setContext($this->context);
			if($this->config){
				$this->instance->setConfig($this->config);
			}
		}
	}
	
	/**
	*
	* @param array $config
	*/
	public function setConfig($config){
		$this->config = $config;
		if($this->instance){
			$this->instance->setConfig($config);
		}
	}
	
	/**
	 * 
	 * @param Class $taskType
	 * @return Boolean
	 */
	public function hasTaskType($taskType){
		return isset($this->taskTypes[$taskType]) && $this->taskTypes[$taskType];
	}
	
	/**
	 * 
	 * @param Class $taskType
	 * @param Object<Class> $task
	 * @return Boolean
	 */
	public function handle($taskType,$task,$security = true){
		if(!$this->security || $security){
			$this->createInstance();
			return $this->instance->handle($taskType,$task);
		}
		return true;
	}
	
	public function setSecurity($security){
		$this->security = $security;
	}
	
	public function getSecurity(){
		return $this->security;
	}
}

class ServiceContainerRemote implements IServiceContainer{
	private $remoteUrl;
	private $taskTypes;
	private $context;
	private $config;
	private $security;
	
	public function ServiceContainerRemote($remoteUrl){
		$this->remoteUrl = $remoteUrl;
		$this->taskTypes = array();
	}
	
	public function setContext($context){
		$this->context = $context;
		if($this->instance){
			$this->instance->setContext($context);
		}
	}
	
	
	/**
	 *
	 * @param Class $taskType
	 */
	public function addTaskType($taskType){
		if(class_exists($taskType)){
			$this->taskTypes[$taskType] = true;
		}
		else{
			throw new ServiceException("Not found task class $taskType", SERVICE_ERROR_NOT_FOUND_TASK_CLASS);
		}
	}
	
	/**
	 *
	 * @param Class $taskType
	 */
	public function removeTaskType($taskType){
		$this->taskTypes[$taskType] = false;
	}
	
	/**
	 *
	 * @return Boolean
	 */
	public function isInstance(){
		return false;
	}
	
	/**
	 * Create IService Instance
	 */
	public function createInstance(){
	}
	
	/**
	 *
	 * @param array $config
	 */
	public function setConfig($config){
		$this->config = $config;
	}
	
	/**
	 *
	 * @param Class $taskType
	 * @return Boolean
	 */
	public function hasTaskType($taskType){
		return isset($this->taskTypes[$taskType]) && $this->taskTypes[$taskType];
	}
	
	/**
	 *
	 * @param Class $taskType
	 * @param Object<Class> $task
	 * @return Boolean
	 */
	public function handle($taskType,$task,$security = true){
		
		if(!$this->security || $security){
			
			$curl = curl_init();
			
			curl_setopt($curl, CURLOPT_URL, $this->$remoteUrl);
			
			curl_setopt($curl, CURLOPT_HEADER,1);
			
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			
			curl_setopt($curl, CURLOPT_POST,1);
			
			$proxy = null;
			$user = null;
			$password = null;
			
			if($this->config){
				$proxy = $this->config["proxy"];
				$user = $this->config["user"];
				$password =$this->config["password"];
			}
			
			if($proxy){
				curl_setopt($curl, CURLOPT_PROXY, $proxy);
			}
			
			if($user && $password){
				curl_setopt($curl, CURLOPT_USERPWD,"$user:$password");
			}
			
			$data = "";
			
			$prefix = $task->prefix();
			
			foreach($task as $key=>$value){
				if($value !== null){
					$data .= "&".$key."=".urlencode($value);
				}
			}
			
			$internalData = $this->context->getInternalData();
			$outputData = $this->context->getOutputData();
			
			foreach($internalData as $key =>$value){
				if($value !== null){
					$data .= "&in-".$key."=".urlencode($value);
				}
			}
			
			$data = curl_exec($curl);
			
			curl_close($curl);
			
			if($data && curl_errno($curl) == 0){
				$data = json_decode($data);
				if($data["error-code"]){
					throw new ServiceException($data["error"], $data["error-code"], 0);
				}
				else{
					$prefix_length = strlen($prefix);
					
					foreach($data as $key=>$value){
						if(substr($key, 0,3) == 'in-'){
							$internalData[substr($key,3)] = $value;
						}
						else if(substr($key,0,$prefix_length) == $prefix){
							$n = substr($key,$prefix_length);
							$task->$n = $value;
						}
						else {
							$outputData[$key] = $value;
						}
					}
					
					return $data["result"];
				}
			}
			else{
				if(curl_errno($curl) == 0){
					throw new ServiceException("Remote access error $this->remoteUrl", SERVICE_ERROR_REMOTE_ERROR);
				}
				else{
					throw new ServiceException(curl_error($curl), SERVICE_ERROR_REMOTE_ERROR);
				}
			}
		}
		return true;
	}
	
	public function setSecurity($security){
		$this->security = $security;
	}
	
	public function getSecurity(){
		return $this->security;
	}
}

class ServiceContainerManager{
	private $containers;
	
	public function ServiceContainerManager(){
		$this->containers = array();
	}
	
	/**
	 * 
	 * @param Class $serviceClass
	 * @return IServiceContainer
	 */
	public function registerService($serviceClass,$serviceContext){
		$container = new ServiceContainer($serviceClass);
		$container->setContext($serviceContext);
		array_push($this->containers,$container);
		return $container;
	}
	
	
	/**
	*
	* task handle
	* @param Class $taskType
	* @param Object<Class> $task
	*
	* @return Boolean
	*/
	public function handle($taskType,$task,$security = true){
		foreach($this->containers as $container){
			if($container->hasTaskType($taskType)){
				if($container->handle($taskType,$task,$security) === false){
					return false;
				}
			}
		}
		return true;
	}
	
	public function setConfig($config,$context){
		foreach($config as $item){
			$container = $this->registerService($item["class"],$context);
			$config = isset($item["config"]) ? $item["config"]:null;
			if($config){
				$container->setConfig($config);
			}
			if(isset($item["createInstance"]) && $item["createInstance"]){
				$container->createInstance();
			}
			$tasks = isset($item["tasks"]) ? $item["tasks"]:null;
			if($tasks){
				foreach($tasks as $value){
					$container->addTaskType($value);
				}
			}
			$security = isset($item["security"]) ? $item["security"]:false;
			$container->setSecurity($security);
		}
	}
}

?>