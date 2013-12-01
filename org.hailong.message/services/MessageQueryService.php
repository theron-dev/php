<?php

/**
 *　消息查询服务
 * Tasks : MessageQueryTask
 * @author zhanghailong
 *
 */
class MessageQueryService extends Service{
	
	public function handle($taskType,$task){
	
		if($task instanceof MessageQueryTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext();
			
			$dbContextTask = new DBContextTask(DB_MESSAGE);
			
			$context->handle("DBContextTask",$dbContextTask);
			
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}
			
			$auth = $context->getInternalDataValue("auth");
			
			$sql = "(tuid={$auth} OR uid={$auth}) AND mstate IN (0,1,2)";
			
			if($task->minMid !== null){
				$sql .= " AND mid > ".intval($task->minMid);
			}
			if($task->maxMid !== null){
				$sql .= " AND mid < ".intval($task->maxMid);
			}
			
			$sql .= " ORDER BY ".$task->orderBy;
			
			if($task->limit !== null){
				$sql .= " LIMIT {$task->limit}";
			}
			
			$rs = $dbContext->queryEntitys("DBMessage",$sql);
			
			$task->results = array();
			
			if($rs){
				
				while($item = $dbContext->nextObject($rs, "DBMessage")){
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