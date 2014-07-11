<?php

class DBService extends Service{
	
	private $dbContexts ;
	
	private static function createDBContext($db){
		$type = isset($db["type"])?$db["type"]:DB_MYSQL;
		$servername = isset($db["servername"]) ? $db["servername"]:null;
		$database = isset($db["database"]) ? $db["database"]:null;
		$username = isset($db["username"]) ? $db["username"]:null;
		$password = isset($db["password"]) ? $db["password"]:null;
		
		if($servername && $database && $username){
			$dbAdapter = newDBAdapter($type,$servername,$database,$username,$password);
			$dbAdapter->connect();
			$dbContext = new DBContext($dbAdapter);
			
			global $CFG_RUNTIME;
			
			if(!$CFG_RUNTIME){
				if(isset($db["entitys"])){
					foreach($db["entitys"] as $entity){
						$dbContext->registerEntity($entity);
					}
				}
			}
			
			return $dbContext;
		}
		
		return null;
	}
	
	public function handle($taskType,$task){
		
		if($task instanceof DBContextTask){

			$config = $this->getConfig();
	
			if(!$this->dbContexts){
				$this->dbContexts = array();
			}
			
			if($task->key !== null){
				if($task->partKey !== null){
					$alias = $task->key."/".$task->partKey;
					if(isset($this->dbContexts[$alias])){
						$task->dbContext = $this->dbContexts[$alias];
					}
					else if(isset($config[$alias])){
						$db = $config[$alias];
						$task->dbContext = DBService::createDBContext($db);
						if($task->dbContext !== null){
							$this->dbContexts[$alias] = $task->dbContext;
						}
					}
				}
				
				if($task->dbContext === null){
					if(isset($this->dbContexts[$task->key])){
						$task->dbContext = $this->dbContexts[$task->key];
					}
					else if(isset($config[$task->key])){
						$db = $config[$task->key];
						$task->dbContext = DBService::createDBContext($db);
						if($task->dbContext !== null){
							$this->dbContexts[$task->key] = $task->dbContext;
						}
					}
				}
			}
			
			
			if($task->dbContext === null){
				$task->dbContext = $this->getContext()->dbContext();
			}
			
			return !$task->dbContext;
		}
		
		return true;
	}
	
	public function setConfig($config){
		parent::setConfig($config);
		if(isset($config["entitys"])){
			$dbContext = $this->getContext()->dbContext();
			global $CFG_RUNTIME;
			if(!$CFG_RUNTIME){
				foreach($config["entitys"] as $entity){
					$dbContext->registerEntity($entity);
				}
			}
		}
	}
	
}

?>