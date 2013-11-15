<?php

/**
 *　Crash服务
 * @author zhanghailong
 *
 */
class CrashService extends Service{
	
	public function handle($taskType,$task){
	
		if($task instanceof CrashTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext(DB_CRASH);
			
			$item = new DBCrash();
			
			$item->identifier = $task->identifier;
			$item->version = $task->version;
			$item->build = $task->build;
			$item->systemName = $task->systemName;
			$item->systemVersion = $task->systemVersion;
			$item->model = $task->model;
			$item->deviceName = $task->deviceName;
			$item->deviceIdentifier = $task->deviceIdentifier;
			
			if(is_string($task->exception)){
				$item->exception = $task->exception;
			}
			else if($task->exception){
				$item->exception = json_encode($task->exception);
			}
			
			$item->createTime = time();
			
			$dbContext->insert($item);
			
			return false;
		}
	
		return true;
	}
}

?>