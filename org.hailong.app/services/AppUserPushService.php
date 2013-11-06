<?php

/**
 * 应用设备推送控制
 * @author zhanghailong
 */
class AppUserPushService extends Service{
	
	public function handle($taskType,$task){
		
		if($task instanceof  AppUserPushTask){
			
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
			$uid = $task->uid;
			$uri = "org.hailong.push";
			$checkFn = $task->settingCheckFn;
			$badgeFn = $task->badgeFn;
			
			if($appid ===null && isset($config["appid"])){
				$appid = $config["appid"];
			}
			if(isset($config["peer-uri"])){
				$uri = $config["peer-uri"];
			}
			
			if($uid === null){
				$uid = $context->getInternalDataValue("auth");
			}
			
			if($appid && $uid ){
				
				if(class_exists("PeerPushTask")){
					$peerTask = new PeerPushTask();
					$peerTask->uri = $uri.".".$uid;
					$peerTask->alert = $task->alert;
					$peerTask->badge = $task->badge;
					$peerTask->sound = $task->sound;
					$peerTask->data = $task->data;
				
					$context->handle("PeerPushTask",$peerTask);
				}
				
				if(class_exists("ApplePushTask")){
					$appleTask = new ApplePushTask();
					$appleTask->alert = $task->alert;
					$appleTask->badge = $task->badge;
					$appleTask->sound = $task->sound;
					$appleTask->data = $task->data;
					$rs = $dbContext->queryEntitys("DBAppAuth","appid=$appid and uid=$uid and (not isnull(did)) and did<>0");
					if($rs){
						while($item = $dbContext->nextObject($rs,"DBAppAuth")){
							
							if($checkFn && !$checkFn($item->setting)){
								continue;
							}
							
							if($badgeFn && $appleTask->badge === null){
								$appleTask->badge = $badgeFn($item);
							}
							
							
							$device = $dbContext->querySingleEntity("DBAppDevice","appid=$appid and did={$item->did}");
							if($device && $device->token){
								
								$appleTask->token = $device->token;
								
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
						}
						$dbContext->free($rs);
					}
				}
			}
			
		}
		
		return true;
	}
}

?>