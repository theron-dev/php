<?php

/**
 *　消息服务
 * Tasks : MessageUserAccessTask,MessageUserInvokeTask,AccountBindTask,MessageAccountAuthorizeTask
 * @author zhanghailong
 *
 */
class MessageService extends Service{
	
	public function handle($taskType,$task){
	
		if($task instanceof AccountBindTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext();
				
			$dbContextTask = new DBContextTask(DB_MESSAGE);
				
			$context->handle("DBContextTask",$dbContextTask);
				
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}
			
			$type = $task->type;
			$uid = $task->uid;
			$key = $task->eid;
			
			if($type && $uid && $key){
				$user = $dbContext->querySingleEntity("DBMessageUser","key='{$key}' and type={$type}");
				if($user){
					if($user->uid != $uid){
						$user->uid =$uid;
						$user->updateTime = time();
						$dbContext->update($user);
					}
				}
				else{
					$user = new DBMessageUser();
					$user->uid = $uid;
					$user->key = $key;
					$user->type = $type;
					$user->source = "org.hailong.account";
					$user->updateTime = time();
					$user->createTime = time();
					$dbContext->insert($user);
				}
			}
		}
		
		if($task instanceof MessageUserAccessTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext();
			
			$dbContextTask = new DBContextTask(DB_MESSAGE);
			
			$context->handle("DBContextTask",$dbContextTask);
			
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}
			
			$user = $dbContext->querySingleEntity("DBMessageUser","key='{$task->key}' and type={$task->type}");
			
			if(!$user){
				$user = new DBMessageUser();
				$user->key = $task->key;
				$user->type = $task->type;
				$user->source = $task->source;
				$user->updateTime = time();
				$user->createTime = time();
				$dbContext->insert($user);
				$task->exists = false;
			}
			else{
				$task->exists =true;
			}

			$task->uid = $user->uid;
			$task->muid = $user->muid;
			
			return false;
			
		}
		
		if($task instanceof MessageUserInvokeTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext();
				
			$dbContextTask = new DBContextTask(DB_MESSAGE);
				
			$context->handle("DBContextTask",$dbContextTask);
				
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}
			
			if(!$task->uid){
				$task->uid = $context->getInternalDataValue("auth");
			}
			
			if($task->type == MessageUserTypeTel){
				$accTask = new AccountByIDTask();
				$accTask->uid = $task->uid;
				
				$context->handle("AccountByIDTask",$accTask);
				
				if(class_exists("AsyncActiveTask")){
						
					$async = new AsyncActiveTask();
					$async->taskType = "SMSSendTask";
					$async->taskClass = "SMSSendTask";
					$data = array();
					$data["sms-tel"] = $task->key;
					$data["sms-body"] = str_replace("{ftitle}", $accTask->title , MESSAGE_SMS_INVOKE_FORMAT);
					$async->data = $data;
				
					$context->handle("AsyncActiveTask",$async);
				}
				else if(class_exists("SMSSendTask")){
					$sms = new SMSSendTask();
					$sms->tel = $task->key;
					$sms->body = str_replace("{ftitle}", $accTask->title , MESSAGE_SMS_INVOKE_FORMAT);
				
					$context->handle("SMSSendTask", $sms);
				}
			}
			else if($task->type == MessageUserTypeEmail){
				
				$accTask = new AccountByIDTask();
				$accTask->uid = $task->uid;
				
				$context->handle("AccountByIDTask",$accTask);
				
				if(class_exists("AsyncActiveTask")){
					
					$async = new AsyncActiveTask();
					$async->taskType = "eMailSendTask";
					$async->taskClass = "eMailSendTask";
					$data = array();
					$data["email-to"] = $task->key;
					$data["email-title"] = MESSAGE_EMAIL_INVOKE_TITLE_FORMAT;
					$data["email-body"] = str_replace("{ftitle}", $accTask->title , MESSAGE_EMAIL_INVOKE_FORMAT);
					$async->data = $data;
				
					$context->handle("AsyncActiveTask",$async);
				}
				else if(class_exists("eMailSendTask")){
					$email = new eMailSendTask();
					$email->to = $task->key;
					$email->title = MESSAGE_EMAIL_INVOKE_TITLE_FORMAT;
					$email->body = str_replace("{ftitle}", $accTask->title , MESSAGE_EMAIL_INVOKE_FORMAT);
				
					$context->handle("eMailSendTask", $email);
				}
			}
	
			return false;
		}
		
		if($task instanceof MessageAccountAuthorizeTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext();
			
			$dbContextTask = new DBContextTask(DB_MESSAGE);
			
			$context->handle("DBContextTask",$dbContextTask);
			
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}
			
			if(!$task->uid){
				$task->uid = $context->getInternalDataValue("auth");
			}
			
			if($task->uid){
				$dbContext->query("UPDATE ".DBMessageUser::tableName()." SET uid={$task->uid} WHERE key='{$task->key}' and type={$task->type}");
			}
			
			return false;
		}
		
		if($task instanceof MessageAttachTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext(DB_MESSAGE);
			
			$mid = intval($task->mid);
			
			$rs = $dbContext->queryEntitys("DBMessageAttach","mid={$mid}");
			
			if($rs){
				
				$task->results = array();
				
				while($item = $dbContext->nextObject($rs,"DBMessageAttach")){
					
					$task->results[] = $item;
					
				}
				
				$dbContext->free($rs);
			}
			
			return false;
		}
		
		return true;
	}
}

?>