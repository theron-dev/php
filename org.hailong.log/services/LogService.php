<?php

/**
 *　日志服务
 * Tasks : LogTask
 * @author zhanghailong
 *
 */
class LogService extends Service{
	
	public function handle($taskType,$task){
	
		if($task instanceof LogTask){
			
			$config = $this->getConfig();
			$filter = LogLevelAll;
			if($config && isset($config["filter"])){
				$filter = intval($config["filter"]);
			}
			
			$context = $this->getContext();
			$dbContext = $context->dbContext();
			
			$dbContextTask = new DBContextTask(DB_LOG);
			
			$context->handle("DBContextTask",$dbContextTask);
			
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}
			
			if($filter & $task->level){
				$log = new DBLog();
				$log->level = $task->level;
				$log->tag = $task->tag;
				$log->source = $task->source;
				$log->body = $task->body;
				$log->createTime = time();
				$dbContext->insert($log);
			}
			
			return false;
		}
		
		if($task instanceof LogClearTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext();
				
			$dbContextTask = new DBContextTask(DB_LOG);
				
			$context->handle("DBContextTask",$dbContextTask);
				
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}
			
			$dbContext->query("DELETE FROM ".DBLog::tableName());
			
			return false;
		}
		
		return true;
	}
}

?>