<?php

/**
 * 推广服务
 * Tasks: SpreadCreateTask SpreadRemoveTask SpreadAskTask
 * @author zhanghailong
 *
 */
class SpreadService extends Service{
	
	public function handle($taskType,$task){
		
		if($task instanceof SpreadCreateTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext();
			
			$dbTask = new DBContextTask();
			$dbTask->key = DB_SPREAD;
			
			$context->handle("DBContextTask", $dbTask);
			
			if($dbTask->dbContext){
				$dbContext = $dbTask->dbContext;
			}
			
			$item = new DBSpread();
			$item->title = $task->title;
			$item->marked = $task->marked;
			$item->type = $task->type;
			$item->askLimit = $task->askLimit;
			$item->askCount = 0;
			$item->updateTime = time();
			$item->createTime = time();
			
			$dbContext->insert($item);
			
			$task->results = $item;
			
			return false;
			
		}

		if($task instanceof SpreadRemoveTask){
				
			$context = $this->getContext();
			$dbContext = $context->dbContext();
			
			$dbTask = new DBContextTask();
			$dbTask->key = DB_SPREAD;
			
			$context->handle("DBContextTask", $dbTask);
			
			if($dbTask->dbContext){
				$dbContext = $dbTask->dbContext;
			}

			$dbContext->delete("DBSpread",array("sid"=>$task->sid));
			
		}
		
		if($task instanceof SpreadAskTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext();
				
			$dbTask = new DBContextTask();
			$dbTask->key = DB_SPREAD;
				
			$context->handle("DBContextTask", $dbTask);
				
			if($dbTask->dbContext){
				$dbContext = $dbTask->dbContext;
			}
			
		
			$rs = $dbContext->queryEntitys("DBSpread","askLimit=0 OR askCount < askLimit ORDER BY updateTime ASC LIMIT {$task->limit}");
			
			if($rs){
				$task->results = array();
				while($item = $dbContext->nextObject($rs,"DBSpread")){
					$item->askCount ++;
					$item->updateTime = time();
					$dbContext->update($item);
					$task->results[] = $item;
				}
				$dbContext->free($rs);
			}
			
			return false;
		}
		
		
		return true;
	}
}

?>