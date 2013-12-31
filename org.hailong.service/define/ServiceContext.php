<?php

class ServiceContext implements IServiceContext{
	
	private $containerManager;
	private $inputData;
	private $internalData;
	private $outputData;
	private $dbContext;
	
	public function ServiceContext($inputData = null,$config = null){
		$this->containerManager = new ServiceContainerManager();
		$this->inputData = $inputData ? $inputData: array();
		$this->internalData = array();
		$this->outputData = array();
		$this->dbContext = getDefaultDBContext();
		if($config){
			$services = isset($config["services"]) ? $config["services"]:array();
			$this->containerManager->setConfig($services,$this);
		}
	}
	
	public function handle($taskType,$task,$security = true){
		return $this->containerManager->handle($taskType,$task,$security);
	}
	
	public function run($config , $security){
		
		$rs = array();
		
		try {
			
			$services = isset($config["services"]) ? $config["services"]:array();
			
			$this->containerManager->setConfig($services,$this);
			
			if(!$security){
				foreach($this->inputData as $key=>$value){
					if(substr($key,0,3) == "in-"){
						$this->internalData[substr($key,3)] = $value;
					}
				}
			}
			
			
			$beginTasks = isset($config["begin-tasks"]) ? $config["begin-tasks"] : null;
			if($beginTasks){
				foreach($beginTasks as $beginTask){
					$taskType = isset($beginTask["taskType"]) ? $beginTask["taskType"] : null;
					$taskClass = isset($beginTask["taskClass"]) ? $beginTask["taskClass"] : null;
					if($taskClass == null){
						$taskClass = $taskType;
					}
					$default = isset($beginTask["default"]) ?  $beginTask["default"]:null ;
					if($taskType){
						$task = new $taskClass();
						$this->fillTask($task,$default);
				
						$this->handle($taskType,$task);
					}
				}
			}
			
			$taskType = isset($this->inputData["taskType"]) ? $this->inputData["taskType"] : null ;
			$taskClass = isset($this->inputData["taskClass"]) ? $this->inputData["taskClass"] : null;
			
			if($taskClass == null){
				$taskClass = $taskType;
			}
			
			if($taskType && class_exists($taskClass)){
				$task = new $taskClass();
				$this->fillTask($task);
				
					
				$rs["result"] = $this->handle($taskType,$task,$security);
				
			}
			
			$endTasks = isset($config["end-tasks"]) ? $config["end-tasks"] : null;
			
			if($endTasks){
				foreach($endTasks as $endTask){
					$taskType = isset($endTask["taskType"]) ? $endTask["taskType"] : null;
					$taskClass = isset($endTask["taskClass"]) ? $endTask["taskClass"] : null;
					$default = $endTask["default"];
					if($taskType){
						$task = new $taskType();
						$this->fillTask($task,$default);
				
						$this->handle($taskType,$task);
					}
				}
			}
			
			
			foreach($this->outputData as $key =>$value){
				if($value !== null){
					$rs[$key] = $value;
				}
			}
			
			if(!$security){
				foreach($this->internalData as $key=>$value){
					if($value != null){
						$rs["in-".$key] =$value;
					}
				}
			}
		}
		catch (ServiceException $ex){
			$rs["error-code"] = $ex->getCode();
			$rs["error"] = $ex->getMessage();
		}
		catch (Exception $ex){
			$rs["error-code"] = $ex->getCode();
			$rs["error"] = $ex->getMessage();
		}
		
		return $rs;
	}
	
	
	public function fillTask($task,$defaultData=null,$namespace=null,$class=null){
		

		if($class == null){
			$this->fillTask($task,$defaultData,$namespace,get_class($task));
		}
		else{
			
			$classs = class_parents($class);
			
			foreach($classs as $c){
				$this->fillTask($task,$defaultData,$namespace,$c);
			}
			
			$values = array();
			$t = new $class();
			$prefix = $t->prefix();
			
			if($namespace){
				if($prefix){
					$prefix = $namespace."-".$prefix;
				}
				else{
					$prefix = $namespace;
				}
			}
			
			foreach($t as $key=>$value){
				if($defaultData && isset($defaultData[$key])){
					$values[$key] = $defaultData[$key];
				}
				$pkey = $prefix ? $prefix."-".$key : $key;
				if(isset($this->inputData[$pkey])){
					$values[$key] = $this->inputData[$pkey];
				}
				if(isset($this->internalData[$pkey])){
					$values[$key] = $this->internalData[$pkey];
				}
			}
			foreach($values as $key=>$value){
				$task->$key = $value;
			}
		}

	}
	
	public function fillData(&$data,$task,$class=null){
		
		if($class == null){
			$this->fillData($data,$task,get_class($task));
		}
		else{
				
			$classs = class_parents($class);
				
			foreach($classs as $c){
				$this->fillData($data,$task,$c);
			}
			
			$values = array();
			$t = new $class();
			$prefix = $t->prefix();
			foreach($t as $key=>$value){
				$v = $task->$key;
				$pkey = $prefix ? $prefix."-".$key : $key;
				if($v !== null){
					$data[$pkey] = $v;
				}
			}

		}
	}

	public function getInputData(){
		return $this->inputData;
	}
	
	public function getInputDataValue($key){
		return isset($this->inputData[$key]) ? $this->inputData[$key]:null;
	}
	
	public function setInputDataValue($key,$value){
		$this->inputData[$key] =  $value;
	}
	
	public function getInternalData(){
		return $this->internalData;
	}
	
	public function getInternalDataValue($key){
		return isset($this->internalData[$key]) ? $this->internalData[$key]:null;
	}
	
	public function setInternalDataValue($key,$value){
		$this->internalData[$key] =  $value;
	}
	
	public function getOutputData(){
		return $this->outputData;
	}
	
	public function getOutputDataValue($key){
		return isset($this->outputData[$key]) ? $this->outputData[$key]:null;
	}
	
	public function setOutputDataValue($key,$value){
		$this->outputData[$key] =  $value;
	}
	
	public function outputTask($task,$keyName=null){
		$a = $this->outputData;
		if($keyName){
			$a = array();
		}
		$prefix = $task->prefix();
		foreach($task as $key=>$value){
			if($value !== null){
				if($prefix){
					$a[$prefix.'-'.$key] = $value;
				}
				else{
					$a[$key] = $value;
				}
			}
		}
		if($keyName){
			$this->outputData[$keyName] = $a;
		}
		else{
			$this->outputData = $a;
		}
	}
	
	public function dbContext($key=null,$partKey=null){
		if($key){
			$task = new DBContextTask();
			$task->key = $key;
			$task->partKey = $partKey;
			$this->handle("DBContextTask", $task);
			if($task->dbContext){
				return $task->dbContext;
			}
		}
		return $this->dbContext;
	}
	
	public function setDbContext($dbContext){
		$this->dbContext = $dbContext;
	}
}

?>