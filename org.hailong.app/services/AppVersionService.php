<?php

/**
 * 应用版本控制
 * @author zhanghailong
 */
class AppVersionService extends Service{
	
	public function handle($taskType,$task){
		
		if($taskType == "AppVersionTask"){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext();
			
			$dbTask = new DBContextTask();
			$dbTask->key = DB_APP;
			
			$context->handle("DBContextTask", $dbTask);
			
			if($dbTask->dbContext){
				$dbContext = $dbTask->dbContext;
			}
			
			
			$appid = $task->appid;
			$platform = $task->platform;
			$version = $task->version;
			
			$appVersion = $dbContext->querySingleEntity("DBAppVersion","`appid`='$appid' AND `platform`='$platform' AND `version`='$version'");

			if($appVersion){
				$context->setOutputDataValue("app-update-version", $appVersion->version);
				$context->setOutputDataValue("app-update-level", $appVersion->updateLevel);
                $context->setOutputDataValue("app-update-content",$appVersion->content);
                $context->setOutputDataValue("app-update-uri",$appVersion->uri);
			}
			
			return false;
		}
		
		return true;
	}
}

?>