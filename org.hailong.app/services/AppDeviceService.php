<?php

/**
 * 应用设备控制
 * @author zhanghailong
 */
class AppDeviceService extends Service{
	
	public function handle($taskType,$task){
		
		if($task instanceof AppDeviceTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext();
			
			$dbTask = new DBContextTask();
			$dbTask->key = DB_APP;
			
			$context->handle("DBContextTask", $dbTask);
			
			if($dbTask->dbContext){
				$dbContext = $dbTask->dbContext;
			}

			
			$config = $this->getConfig();
			$appid = $task->appid;
			$did = $task->did;
			$token = $task->token;
			$version = $task->version;
			$build = $task->build;
			
			if($appid ===null && isset($config["appid"])){
				$appid = $config["appid"];
			}
			
			if($did === null){
				$did = $context->getInternalDataValue("device-did");
			}

			if($appid && $did){
				
				$item = $dbContext->querySingleEntity("DBAppDevice","`appid`=$appid AND did=$did");
                
				$hasTokenChange = false;
				$hasChange = false;
				
				if($item){
					
					if($item->token != $token){
						$item->token = $token;
						$hasTokenChange = true;
						$hasChange = true;
					}
					
					if($version && $item->version != $version){
						$item->version = $version;
						$hasChange = true;
					}
					
					if($build && $item->build != $build){
						$item->build = $build;
						$hasChange = true;
					}
					
					if($hasChange){
						$item->updateTime = time();
						$dbContext->update($item);
					}
				}
				else{
					$item = new DBAppDevice();
					$item->appid = $appid;
					$item->did = $did;
					$item->token = $token;
					$item->version = $version;
					$item->build = $build;
					$item->updateTime = time();
					$item->createTime = time();
					$dbContext->insert($item);
					$hasTokenChange = true;
				}
			
				if($hasTokenChange){
					$dbContext->delete("DBAppDevice","`appid`=$appid AND `token`=".$dbContext->parseValue($token)." AND `did`<>$did");
				}
				
			}
			
			return false;
		}
		
		return true;
	}
}

?>