<?php

/**
 * 异步任务服务
 * @author zhanghailong
 *
 */
class AsyncTaskService extends Service{
	
	public function handle($taskType,$task){
		
		$context = $this->getContext();
		$dbContext = $context->dbContext();

		$dbTask = new DBContextTask();
		$dbTask->key = DB_ASYNC;
		
		$context->handle("DBContextTask", $dbTask);
		
		if($dbTask->dbContext){
			$dbContext = $dbTask->dbContext;
		}
		
		if($task instanceof AsyncActiveTask){
			
			$config = $this->getConfig();
			
			if($task->config=== null && isset($config["config"])){
				$task->config = $config["config"];
			}
			
			if($task->taskType && $task->taskClass && $task->config){
				
				
				$item = new DBAsyncTask();
				$item->config = $task->config;
				$item->taskType = $task->taskType;
				$item->taskClass = $task->taskClass;
				$item->data = json_encode($task->data);
				$item->rank = $task->rank;
				$item->createTime = time();
				
				$dbContext->insert($item);
				
				global $async_lock;
				global $sync_php;
				
				$lock = $async_lock.".".$item->rank.".async";
				
				if(!file_exists($lock)){
					
					if(strtoupper(substr(PHP_OS, 0, 3)) === 'WIN'){
						system("start /b php {$sync_php} \"{$item->rank}\" > %TEMP%\{$item->rank}.async.tmp");
					}
					else{
						system("php {$sync_php} \"{$item->rank}\" >/tmp/{$item->rank}.async.tmp &");
					}
				}
				
			}
			
			return false;
		}
		else if($task instanceof AsyncResetTask){
			
			$item = $dbContext->get("DBAsyncTask",array("atid"=>$task->atid));
			
			if($item ){
				$item->state = AsyncTaskStateNone;
				$item->createTime = time();
				$dbContext->update($item);
				
				global $async_lock;
				global $sync_php;
					
				$lock = $async_lock.".".$item->rank.".async";
				
				if(!file_exists($lock)){
				
					if(strtoupper(substr(PHP_OS, 0, 3)) === 'WIN'){
						system("start /b php {$sync_php} \"{$item->rank}\" > %TEMP%\async.tmp");
					}
					else{
						system("php {$sync_php} \"{$item->rank}\" >/tmp/async.tmp &");
					}
				}
			}

			return false;
		}
		else if($task instanceof AsyncCleanupTask){
			
			$dbContext->delete("DBAsyncTask"," (state=".AsyncTaskStateOK." or state=".AsyncTaskStateError.")");
			
			return false;
		}
		
		return true;
	}
}

?>