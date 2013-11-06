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
			
			if($appid ===null && isset($config["appid"])){
				$appid = $config["appid"];
			}
			
			if($did === null){
				$did = $context->getInternalDataValue("device-did");
			}

			if($appid && $did && $token ){
				
				$item = $dbContext->querySingleEntity("DBAppDevice","`appid`=$appid AND did=$did");
                
				$hasChange = false;
				
				if($item){
					if($item->token != $token){
						$item->token = $token;
						$item->updateTime = time();
						$dbContext->update($item);
						$hasChange = true;
					}
				}
				else{
					$item = new DBAppDevice();
					$item->appid = $appid;
					$item->did = $did;
					$item->token = $token;
					$item->updateTime = time();
					$item->createTime = time();
					$dbContext->insert($item);
					$hasChange = true;
				}
			
				if($hasChange){
					$dbContext->delete("DBAppDevice","`appid`=$appid AND `token`=".$dbContext->parseValue($token)." AND `did`<>$did");
				}
				
			}
			
			return false;
		}
		
		return true;
	}
}

?>