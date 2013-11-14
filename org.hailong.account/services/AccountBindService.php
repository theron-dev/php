<?php

/**
 * 帐号绑定服务
 * @author zhanghailong
 * @Tasks AccountBindGetTask , AccountBindTask
 */
class AccountBindService extends Service{
	
	public function handle($taskType,$task){
		

		if($task instanceof AccountBindTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext();
			
			$dbTask = new DBContextTask();
			$dbTask->key = DB_ACCOUNT;
			
			$context->handle("DBContextTask", $dbTask);
			
			if($dbTask->dbContext){
				$dbContext = $dbTask->dbContext;
			}
			
			
			if(!$task->uid){
				$task->uid = $context->getInternalDataValue("auth");
			}
			
			$bind = $dbContext->querySingleEntity("DBAccountBind","uid={$task->uid} and type={$task->type} and appKey='{$task->appKey}'");
			
			$context->setOutputDataValue("sql", $dbContext->getLastSql());
			
			if($bind){
				$bind->eid = $task->eid;
				$bind->appSecret = $task->appSecret;
				$bind->etoken = $task->etoken;
				$bind->expires_in = $task->expires_in + time();
				$bind->updateTime = time();
				$dbContext->update($bind);
			}
			else{
				$bind = new DBAccountBind();
				$bind->uid = $task->uid;
				$bind->appKey = $task->appKey;
				$bind->appSecret = $task->appSecret;
				$bind->type = $task->type;
				$bind->etoken = $task->etoken;
				$bind->expires_in = $task->expires_in + time();
				$bind->eid = $task->eid;
				$bind->updateTime = time();
				$bind->createTime = time();
				$dbContext->insert($bind);
			}
			
			return false;
		}
		
		if($task instanceof AccountBindGetTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext();
			
			$dbTask = new DBContextTask();
			$dbTask->key = DB_ACCOUNT;
			
			$context->handle("DBContextTask", $dbTask);
			
			if($dbTask->dbContext){
				$dbContext = $dbTask->dbContext;
			}
			
			if(!$task->uid){
				$task->uid = $context->getInternalDataValue("auth");
			}
			
			$bind = $dbContext->querySingleEntity("DBAccountBind","uid={$task->uid} and type={$task->type} and appKey='{$task->appKey}'");
			
			if($bind && time() - $bind->updateTime < $bind->expires_in){
				$task->appSecret= $bind->appSecret;
				$task->eid = $bind->eid;
				$task->etoken = $bind->etoken;
				$task->expires_in = $bind->expires_in;
			}
			
			return false;
		}
		
		return true;
	}
}

?>