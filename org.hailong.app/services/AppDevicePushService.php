<?php

/**
 * 应用设备推送控制
 * @author zhanghailong
 */
class AppDevicePushService extends Service{
	
	public function handle($taskType,$task){
		
		if($taskType == "AppDevicePushTask"){
			
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
			$uri = "org.hailong.push.device";
			
			if($appid ===null && isset($config["appid"])){
				$appid = $config["appid"];
			}
			if(isset($config["peer-uri"])){
				$uri = $config["peer-uri"];
			}
			
			if($did === null){
				$did = $context->getInternalDataValue("device-did");
			}
			
			if($appid && $did ){
				
				$item = $dbContext->querySingleEntity("DBAppDevice","appid=$appid and did=$did");
                
				if($item){
					if($item->token && class_exists("ApplePushTask")){
						
						$appleTask = new ApplePushTask();
						$appleTask->token = $item->token;
						$appleTask->alert = $task->alert;
						$appleTask->badge = $task->badge;
						$appleTask->sound = $task->sound;
						$appleTask->data = $task->data;
						
						if(class_exists("AsyncActiveTask")){
							$asyncTask = new AsyncActiveTask();
							$asyncTask->config = isset($config["async-config"]) ? $config["async-config"] : null;
							$asyncTask->taskType = "ApplePushTask";
							$asyncTask->taskClass = "ApplePushTask";
							$asyncTask->rank = "applepush";
								
							$data = array();
							$context->fillData($data, $appleTask);
								
							$asyncTask->data = $data;
								
							$context->handle("AsyncActiveTask",$asyncTask);
						}
						else{
							$context->handle("ApplePushTask",$appleTask);
						}
						
					}
					if(class_exists("PeerPushTask")){
						$peerTask = new PeerPushTask();
						$peerTask->uri = $uri.".".$item->did;
						$peerTask->alert = $task->alert;
						$peerTask->badge = $task->badge;
						$peerTask->sound = $task->sound;
						$peerTask->data = $task->data;
						
						$context->handle("PeerPushTask",$peerTask);
					}
				}
			}
			
		}
		
		return true;
	}
}

?>