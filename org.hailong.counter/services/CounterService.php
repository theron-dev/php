<?php

/**
 * 计数器服务
 * @Tasks CounterAddTask,CounterPullTask
 * @author zhanghailong
 *
 */
class CounterService extends Service{
	
	public function handle($taskType,$task){
	
		if($task instanceof CounterAddTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext(DB_COUNTER);
				
			$uid = $context->getInternalDataValue("auth");
			
			$item = new DBCounter();
			
			$item->tuid = $task->tuid;
			$item->target = $task->target;
			$item->tid = $task->tid;
			$item->fuid = $context->getInternalDataValue("auth");
			$item->ftype = $task->ftype;
			$item->fid = $task->fid;
			$item->count = $task->count;
			$item->createTime = time();
			
			$dbContext->insert($item);
			
			$task->results = $item;
	
			return false;
		}
		
		if($task instanceof CounterPullTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext(DB_COUNTER);

			$targets = $task->targets;
			
			if($targets && is_array($targets) && count($targets)){
				
				if($task->lastId){
				
					$dbContext->delete("DBCounter","cid <= ".intval($task->lastId)." AND target IN ".$dbContext->parseArrayValue($targets));
				
				}
				
				$results = array();
				
				$sql = "SELECT target,SUM(`count`) as count,MAX(cid) as cid FROM `".DBCounter::tableName()."` WHERE target IN ".$dbContext->parseArrayValue($targets)." GROUP BY target";
				
				$rs = $dbContext->query($sql);
				
				if($rs){
					
					while($row = $dbContext->next($rs)){
						
						$item = array();
						
						$item["cid"] = $row["cid"];
						$item["count"] = $row["count"];
						
						$results[$row["target"]] = $item;
						
					}
					
					$dbContext->free($rs);
				}
				
				$task->results = $results;
				
			}
		
		}
		
		return true;
	}
}

?>