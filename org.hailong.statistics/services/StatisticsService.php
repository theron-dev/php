<?php

/**
 * 统计服务
 * Tasks: StatisticsTask
 * @author zhanghailong
 *
 */
class StatisticsService extends Service{
	
	public function handle($taskType,$task){
		
		$context = $this->getContext();
		$dbContext = $context->dbContext();

		$dbTask = new DBContextTask();
		$dbTask->key = DB_STATISTICS;
		
		$context->handle("DBContextTask", $dbTask);
		
		if($dbTask->dbContext){
			$dbContext = $dbTask->dbContext;
		}
		
		$config = $this->getConfig();
		$dbClass = isset($config["dbClass"]) ? $config["dbClass"] : "DBStatisticsUniversal";
		$timeout = isset($config["timeout"]) ? $config["timeout"] : 30;
		$beginTime = isset($config["beginTime"]) ? $config["beginTime"] : 0;
		
		
		if($task instanceof StatisticsTask){
			
			if($task->target && $task->key){
				
				$uid = $context->getInternalDataValue("auth");
				
				if($uid === null){
					$uid = 0;
				}
				
				$cachePath = CACHE_PATH_STATISTICS."/".$dbClass."/".$task->target."/".$task->source."/".$uid;
				$value = 0;
				$storageTime = 0;
				
				$cacheTask = new CacheGetTask();
				$cacheTask->path = $cachePath;
				
				$context->handle("CacheGetTask",$cacheTask);
				
				if($cacheTask->value){
					$value = isset($cacheTask->value["v"]) ? $cacheTask->value["v"]: 0;;
					$storageTime = isset($cacheTask->value["t"]) ? $cacheTask->value["t"]: 0;
				}

				if(($storageTime == 0 && time() >= $beginTime) || (time() - $storageTime >= $timeout )){

					$key = $task->key;
					
					$item  = $dbContext->querySingleEntity($dbClass,"uid=$uid and target='{$task->target}' and source='{$task->source}' and classifyTime={$task->classifyTime}");
					
					if($item){
						$item->$key = $item->$key + $task->count + $value;
						$item->updateTime = time();
						$dbContext->update($item);
					}
					else{
						$item = new $dbClass();
						$item->target = $task->target;
						$item->source = $task->source;
						$item->classifyTime = $task->classifyTime;
						$item->uid = $uid;
						$item->sessionId = isset($_SESSION) ? session_id() : '';
						$item->updateTime = time();
						$item->createTime = time();
						$item->$key = $task->count + $value;
						$dbContext->insert($item);
					}

					$cacheTask = new CachePutTask();
					$cacheTask->path = $cachePath;
					$cacheTask->value = array("v"=>0,"t"=>$item->updateTime);
						
					$context->handle("CachePutTask",$cacheTask);
					
				}
				else{
					$cacheTask = new CachePutTask();
					$cacheTask->path = $cachePath;
					$cacheTask->value =  array("v"=>$value + $task->count,"t"=>$storageTime) ;
					$context->handle("CachePutTask",$cacheTask);	
				}
			}

			
			return false;
			
		}
		
		return true;
	}

}

?>