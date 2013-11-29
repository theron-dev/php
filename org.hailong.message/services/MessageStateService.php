<?php

/**
 *　消息状态服务
 * Tasks : MessageStateTask , MessageSessionStateTask
 * @author zhanghailong
 *
 */
class MessageStateService extends Service{
	
	public function handle($taskType,$task){
	
		if($task instanceof MessageStateTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext();
			
			$dbContextTask = new DBContextTask(DB_MESSAGE);
			
			$context->handle("DBContextTask",$dbContextTask);
			
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}
			
			$auth = $context->getInternalDataValue("auth");
			
			$msg = $dbContext->querySingleEntity("DBMessage","mid={$task->mid} and tuid={$auth}");
			
			if($msg && $msg->mstate < $task->mstate){
				
				$msg->mstate = $task->mstate;
				$msg->updateTime = time();
				$dbContext->update($msg);
				
				if($msg->uid){
					$remind = new MessageRemindTask();
					$remind->mid = $task->mid;
					$remind->uid = $msg->uid;
					
					$context->handle("MessageRemindTask", $remind);
				}
			}
			
			return false;
		}
		
		if($task instanceof MessageSessionStateTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext();
				
			$dbContextTask = new DBContextTask(DB_MESSAGE);
				
			$context->handle("DBContextTask",$dbContextTask);
				
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}
	

			$uid = $context->getInternalDataValue("auth");
			
			$sql = "mstate<{$task->mstate} AND tuid={$uid}";
			
			if($task->maxMid !== null){
				$sql .= " AND mid<={$task->maxMid}";
			}
			
			if($task->uid !== null){
				$sql .= " AND uid={$task->uid}";
			}
			
			$sql .= " ORDER BY mid ASC";
			
			$rs = $dbContext->queryEntitys("DBMessage",$sql);
			
			if($rs){
				while($item = $dbContext->nextObject($rs,"DBMessage")){
					$t = new MessageStateTask();
					$t->mid = $item->mid;
					$t->mstate = $task->mstate;
					
					$context->handle("MessageStateTask",$t);
				}
				$dbContext->free($rs);
			}
			
			return false;
		}
		
		return true;
	}
}

?>