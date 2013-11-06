<?php

/**
 * Taobao登陆服务
 * @author zhanghailong
 *
 */
class TaobaoLoginService extends Service{
	
	public function handle($taskType,$task){
		
		if($task instanceof TaobaoLoginTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext();
			
			$dbTask = new DBContextTask();
			$dbTask->key = DB_ACCOUNT;
			
			$context->handle("DBContextTask", $dbTask);
			
			if($dbTask->dbContext){
				$dbContext = $dbTask->dbContext;
			}
		
			$cfg = $this->getConfig();
			
			if(!$task->appKey && isset($cfg["appKey"])){
				$task->appKey = $cfg["appKey"];
			}
				
			if(!$task->appSecret && isset($cfg["appSecret"])){
				$task->appSecret = $cfg["appSecret"];
			}
			
			$taobao_uid = $task->taobao_uid;
			
			$user = $dbContext->querySingleEntity("DBAccount","taobao_uid='{$taobao_uid}' and account='#TB_{$taobao_uid}'");
			
			if($user){
				$auth = $user->uid;
				
				if($user->state == AccountStateDisabled){
					throw new AccountException("user is disabled", ERROR_USER_IS_DISABLED);
				}
				
				if($user->state == AccountStateGenerated){
					$user->state = AccountStateNone;
					$this->importRelation($context,$dbContext,$task->appKey,$task->etoken,$openid,$auth);
				}
				
				$user->loginTime = time();

				$dbContext->update($user);
				$_SESSION["auth"] = $auth;
				$context->setInternalDataValue("auth", $auth);
				
				$bind = new AccountBindTask();
				$bind->type = AccountBindTypeTaobao;
				$bind->appKey = $task->appKey;
				$bind->appSecret = $task->appSecret;
				$bind->eid = $user->taobao_uid;
				$bind->etoken = $task->etoken;
				$bind->expires_in = $task->expires_in;
				
				$context->handle("AccountBindTask",$bind);
				
				if(class_exists("LogTask")){
					$log = new LogTask(LogLevelInfo,"account","qq uid:{$openid} login uid:".$auth);
					$context->handle("LogTask",$log);
				}
			}
			else{

				$user = new DBAccount();
				$user->account = "#TB_".$taobao_uid;
				$user->taobao_uid = $taobao_uid;
				$user->password = DBAccount::generatedPassword();
				$user->title = $task->nick;
				$user->state = AccountStateNone;
				$user->loginTime = time();
				$user->updateTime = time();
				$user->createTime = time();
				
				$dbContext->insert($user);
				
				$auth = $user->uid;
				
				$_SESSION["auth"] = $auth;
				$context->setInternalDataValue("auth", $auth);
				
				$bind = new AccountBindTask();
				$bind->type = AccountBindTypeTaobao;
				$bind->appKey = $task->appKey;
				$bind->appSecret = $task->appSecret;
				$bind->eid = $user->taobao_uid;
				$bind->etoken = $task->etoken;
				$bind->expires_in = $task->expires_in;
				
				$context->handle("AccountBindTask",$bind);
				
				if(class_exists("LogTask")){
					$log = new LogTask(LogLevelInfo,"account","qq uid:{$openid} login uid:".$auth);
					$context->handle("LogTask",$log);
				}

			}

			return false;
		}
		
		return true;
	}
	
}

?>