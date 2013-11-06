<?php

/**
 * URI服务
 * @author zhanghailong
 * @task URITask , URIAssignTask
 */
class URIService extends Service{
	
	public function handle($taskType,$task){
		
		if($taskType == "URITask"){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext();
				
			$dbContextTask = new DBContextTask(DB_URI);
				
			$context->handle("DBContextTask",$dbContextTask);
				
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}
			
			$item = $dbContext->querySingleEntity("DBURI","url='{$task->url}'");
			
			if(!$item){
				$item = new DBURI();
				$item->url = $task->url;
				$item->browseCount = 0;
				$item->createTime = time();
				$dbContext->insert($item);
			}
			
			$task->uri = DBURI::URIEncode($item->uri);
			
			return false;
		}
		
		if($taskType == "URIAssignTask"){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext();
			
			$dbContextTask = new DBContextTask(DB_URI);
			
			$context->handle("DBContextTask",$dbContextTask);
			
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}
				
			$uri = DBURI::URIDecode($task->uri);
			
			if($uri){
				$item = $dbContext->get("DBURI",array("uri"=>$uri));
				if($item){
					$dbContext->query("UPDATE ".DBURI::tableName()." SET browserCount=browserCount+1 WHERE uri={$uri}");
					$task->url = $item->url;
				}
			}
			return false;
		}
		
	}
}

?>