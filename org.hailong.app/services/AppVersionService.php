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
			
			$cfg = $this->getConfig();
			
			$appid = isset($cfg["appid"]) ? $cfg["appid"] : intval($task->appid);
			
			$platform = intval($task->platform);
			$version = $dbContext->parseValue( $task->version );
			
			$appVersion = $dbContext->querySingleEntity("DBAppVersion","`appid`=$appid AND `platform`=$platform AND `version`=$version");

			if($appVersion){
				
				$item = $dbContext->querySingleEntity("DBAppVersion","`appid`=$appid AND `platform`=$platform AND isLastVersion=1");
				
				if($item){
					$context->setOutputDataValue("app-version", $item->version);
				}
				else{
					$context->setOutputDataValue("app-version", $appVersion->version);
				}
				
				$context->setOutputDataValue("app-level", $appVersion->updateLevel);
                $context->setOutputDataValue("app-content",$appVersion->content);
                $context->setOutputDataValue("app-uri",$appVersion->uri);
			}
			
			return false;
		}
		
		if($task instanceof AppVersionCreateTask){
				
			$context = $this->getContext();
			$dbContext = $context->dbContext(DB_APP);
				
				
			$appid = intval($task->appid);
			$platform = intval($task->platform);
			$version = $dbContext->parseValue( $task->version );
				
			$item = $dbContext->querySingleEntity("DBAppVersion","`appid`=$appid AND `platform`=$platform AND `version`=$version");
		
			if($item){
		
				$item->content = $task->content;
				$item->uri = $task->uri;
				$item->updateLevel = $task->updateLevel;
				$item->timestamp = time();
				
				$dbContext->update($item);
			}
			else{
				
				$item = new DBAppVersion();
				$item->appid = $appid;
				$item->platform = $platform;
				$item->version = $task->version;
				$item->content = $task->content;
				$item->uri = $task->uri;
				$item->updateLevel = $task->updateLevel;
				$item->timestamp = time();
				
				$dbContext->insert($item);
			}
				
			return false;
		}
		
		if($task instanceof AppVersionUpdateTask){
		
			$context = $this->getContext();
			$dbContext = $context->dbContext(DB_APP);
		
			$item = $dbContext->querySingleEntity("DBAppVersion","`avid`=".intval($task->avid));
		
			if($item){
		
				$item->content = $task->content;
				$item->uri = $task->uri;
				$item->updateLevel = $task->updateLevel;
				$item->timestamp = time();
		
				$dbContext->update($item);
			}
			
			return false;
		}
		
		if($task instanceof AppVersionRemoveTask){
		
			$context = $this->getContext();
			$dbContext = $context->dbContext(DB_APP);
		
			$dbContext->delete("DBAppVersion","`avid`=".intval($task->avid));
		
			return false;
		}
		
		if($task instanceof AppVersionSetLastTask){
		
			$context = $this->getContext();
			$dbContext = $context->dbContext(DB_APP);
		
			$avid = intval($task->avid);
			
			$item = $dbContext->querySingleEntity("DBAppVersion","`avid`=".$avid);
		
			if($item){
				
				$dbContext->query("UPDATE ".DBAppVersion::tableName()." SET isLastVersion=1 WHERE avid=".$avid);
				$dbContext->query("UPDATE ".DBAppVersion::tableName()." SET isLastVersion=0 WHERE avid<>".$avid);
			}
			
			return false;
		}
		
		return true;
	}
}

?>