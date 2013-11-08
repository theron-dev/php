<?php

/**
 * @用户服务
 * @author zhanghailong
 * @task ATUserCreateTask , ATUserRemoveTask , ATUserBodyTask , ATUserFetchTask
 */
class ATUserService extends Service{
	
	public function handle($taskType,$task){
		
		if($task instanceof ATUserCreateTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext();
			
			$dbContextTask = new DBContextTask(DB_ATUSER);
			
			$context->handle("DBContextTask",$dbContextTask);
			
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}
			
			$uid = $task->uid;
			
			if($uid === null){
				$uid = $context->getInternalDataValue("auth");
			}
			
			$item = $dbContext->querySingleEntity("DBATUser","uid={$uid} and tuid={$task->tuid} and etype={$task->etype} and eid={$task->eid} and ttype={$task->ttype} and tid={$task->tid}");
			
			if(!$item){
				$item = new DBATUser();
				$item->uid = $uid;
				$item->tuid = $task->tuid;
				$item->etype = $task->etype;
				$item->eid = $task->eid;
				$item->ttype = $task->ttype;
				$item->tid = $task->tid;
				$item->createTime = time();
				$dbContext->insert($item);
			}

			$task->results = $item;
			
			return false;
		}
		
		if($task instanceof ATUserRemoveTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext();
				
			$dbContextTask = new DBContextTask(DB_ATUSER);
				
			$context->handle("DBContextTask",$dbContextTask);
				
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}
			
			$uid = $task->uid;
			
			if($uid === null){
				$uid = $context->getInternalDataValue("auth");
			}
			
			$dbContext->delete("DBATUser","uid={$uid} and etype={$task->etype} and eid={$task->eid} and ttype={$task->ttype} and tid={$task->tid}");
			
			return false;
		}
		
		if($task instanceof ATUserBodyTask){
				
			$context = $this->getContext();
			$dbContext = $context->dbContext();
		
			$dbContextTask = new DBContextTask(DB_ATUSER);
		
			$context->handle("DBContextTask",$dbContextTask);
		
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}
				
			$uid = $task->uid;
				
			if($uid === null){
				$uid = $context->getInternalDataValue("auth");
			}
			
			$t = new ATUserCreateTask();
			$t->uid = $uid;
			$t->etype = $task->etype;
			$t->eid = $task->eid;
			$t->ttype = $task->ttype;
			$t->tid = $task->tid;
			
			$task->results = array();
			
			$len = strlen($task->body);
			$nick = "";
			$s = 0;
			for($i=0;$i<$len;$i++){
				$c = substr($task->body,$i,1);
				if($s == 0 ){
					if($c == "@"){
						$s = 1;
					}
				}
				else if($s == 1){
					if($c == " " || $c == "\t" || $c == "\n" || $c == "\r"){
						$nickTask = new AccountIDCheckNickTask();
						$nickTask->nick = $nick;
						
						$context->handle("AccountIDCheckNickTask",$nickTask);
						
						if($nickTask->uid !== null){
							$t->tuid = $nickTask->uid;
							
							$context->handle("ATUserCreateTask", $t);
							
							if($t->results){
								$task->results[] = $t->results;
							}
						}
						
						$s = 0;
						$nick = "";
					}
					else{
						$nick .= $c;
					}
				}
			}
			
			if($nick){
				
				$nickTask = new AccountIDCheckNickTask();
				$nickTask->nick = $nick;
				
				$context->handle("AccountIDCheckNickTask",$nickTask);
				
				if($nickTask->uid !== null){
					$t->tuid = $nickTask->uid;
						
					$context->handle("ATUserCreateTask", $t);
						
					if($t->results){
						$task->results[] = $t->results;
					}
				}
			}
			
			return false;
		}
		
		if($task instanceof ATUserFetchTask){
		
			$context = $this->getContext();
			$dbContext = $context->dbContext();
		
			$dbContextTask = new DBContextTask(DB_ATUSER);
		
			$context->handle("DBContextTask",$dbContextTask);
		
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}
		
			$uid = $task->uid;
		
			if($uid === null){
				$uid = $context->getInternalDataValue("auth");
			}
				
			$sql = "tuid={$uid} ORDER BY atuid DESC";
			
			if($task->pageIndex !== null && $task->pageSize !== null){
				$offset = ($task->pageIndex -1) * $task->pageSize;
				$sql .= " LIMIT {$offset},{$task->pageSize}";
			}
			
			$rs = $dbContext->queryEntitys("DBATUser",$sql);
			
			if($rs){
				while($item = $dbContext->nextObject($rs, "DBATUser")){
					if(!$task->fetchItem($item)){
						break;
					}
				}
				$dbContext->free($rs);
			}
			
			return false;
		}
		
		return true;
	}
	
	
}

?>