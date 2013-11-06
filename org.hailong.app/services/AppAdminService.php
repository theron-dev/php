<?php

/**
 * 应用管理服务
 * @author zhanghailong
 */
class AppAdminService extends Service{
	
	public function handle($taskType,$task){
		
		if($taskType == "AppCreateTask"){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext();
			
			$dbTask = new DBContextTask();
			$dbTask->key = DB_APP;
			
			$context->handle("DBContextTask", $dbTask);
			
			if($dbTask->dbContext){
				$dbContext = $dbTask->dbContext;
			}
			
			$auth = $task->uid;
			
			if($auth == null){
				$auth = $context->getInternalDataValue("auth");
			}
			
			$app = new DBApp();
			
			$app->appid = $task->appid;
			$app->uid = $auth;
			$app->title = $task->title;
			$app->description = $task->description;
			$app->secret = rand();
			$app->createTime = time();
			
			$dbContext->insert($app,$task->appid != null);
			
			return false;
		}
		else if($taskType == "AppUpdateTask"){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext();
			
			$dbTask = new DBContextTask();
			$dbTask->key = DB_APP;
			
			$context->handle("DBContextTask", $dbTask);
			
			if($dbTask->dbContext){
				$dbContext = $dbTask->dbContext;
			}
			
			$app = $dbContext->get("DBApp",array("appid"=>$task->appid));
			
			if($app){
				$app->title = $task->title;
				$app->description = $task->description;
				$dbContext->update($app);
			}
			
			return false;
		}
		else if($taskType == "AppRemoveTask"){
				
			$context = $this->getContext();
			$dbContext = $context->dbContext();
				
			$dbTask = new DBContextTask();
			$dbTask->key = DB_APP;
				
			$context->handle("DBContextTask", $dbTask);
				
			if($dbTask->dbContext){
				$dbContext = $dbTask->dbContext;
			}
				
			$dbContext->delete("DBApp",array("appid"=>$task->appid));
				
			return false;
		}
		
		return true;
	}
}

?>