<?php

/**
 *　消息提醒服务
 * Tasks : MessageRemindTask
 * @author zhanghailong
 *
 */
class MessageRemindService extends Service{
	
	public function handle($taskType,$task){
	
		if($task instanceof MessageRemindTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext();
			
			$dbContextTask = new DBContextTask(DB_MESSAGE);
			
			$context->handle("DBContextTask",$dbContextTask);
			
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}
			
			if(class_exists("AppUserPushTask")){
				
				$item = $dbContext->get("DBMessage",array("mid"=>$task->mid));
				
				if($item){
					
					global $MESSAGE_SETTING_CHECK_FN;
					global $MESSAGE_BADGE_FN;
					
					if($item->mstate == MessageStateNone){
						$alert = "";
						if($item->tid){
							$alert .= "群消息: ".$item->body;
						}
						else{
							$nickTask = new AccountInfoGetTask();
							$nickTask->uid = $item->uid;
							$nickTask->keys = array(AccountInfoKeyNick);
								
							$context->handle("AccountInfoGetTask",$nickTask);
								
							$nick = isset($nickTask->infos[AccountInfoKeyNick]['value']) ? $nickTask->infos[AccountInfoKeyNick]['value'] : null;
							
							if(!$nick){
								$accTask = new AccountByIDTask();
								$accTask->uid = $item->uid;
								$context->handle("AccountByIDTask",$accTask);
								$nick = $accTask->title;
							}
							
							$alert .= $nick.": ".$item->body;
						}
						
						if(strlen($alert) > 140){
							$alert  = substr($alert, 137)."...";
						}
						
						$pushTask =  new AppUserPushTask();
						$pushTask->settingCheckFn = $MESSAGE_SETTING_CHECK_FN;
						$pushTask->badgeFn = $MESSAGE_BADGE_FN;
						$pushTask->uid = $task->uid;
						$pushTask->alert = $alert;
						$pushTask->sound = "default";
						$data = array("mid"=>$item->mid,"mstate"=>$item->mstate,"type"=>"M");
						if($item->tid){
							$data["tid"] = $item->tid;
						}
						if($item->uid){
							$data["uid"] = $item->uid;
						}
						if($item->tuid){
							$data["tuid"] = $item->tuid;
						}
						$pushTask->data = $data;
						$context->handle("AppUserPushTask",$pushTask);
					}
					else{
						$pushTask =  new AppUserPushTask();
						$pushTask->settingCheckFn = $MESSAGE_SETTING_CHECK_FN;
						$pushTask->badgeFn = $MESSAGE_BADGE_FN;
						$pushTask->uid = $task->uid;
						$data = array("mid"=>$item->mid,"mstate"=>$item->mstate,"type"=>"M");
						if($item->tid){
							$data["tid"] = $item->tid;
						}
						if($item->uid){
							$data["uid"] = $item->uid;
						}
						if($item->tuid){
							$data["tuid"] = $item->tuid;
						}
						$pushTask->data = $data;	
						$context->handle("AppUserPushTask",$pushTask);
					}
					
				}
			}
			
			return false;
		}
		
		return true;
	}
}

?>