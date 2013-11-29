<?php

/**
 *　消息会议服务
 * Tasks : MessageMemberTask
 * @author zhanghailong
 *
 */
class MeetingService extends Service{
	
	public function handle($taskType,$task){
	
		if($task instanceof MeetingMemberTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext();
				
			$dbContextTask = new DBContextTask(DB_MESSAGE);
				
			$context->handle("DBContextTask",$dbContextTask);
				
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}
			
			$rs = $dbContext->queryEntitys("DBMeetingMember","tid={$task->tid} ORDER BY createTime ASC");
			
			$task->results = array();
			
			if($rs){
				
				while($item = $dbContext->nextObject($rs,"DBMeetingMember")){
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