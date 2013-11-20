<?php

/**
 * 喜欢服务
 * @author zhanghailong
 * @task LikeTask , UnLikeTask , LikeRemoveTask
 */
class LikedService extends Service{
	
	public function handle($taskType,$task){
		
		if($taskType == "LikeTask"){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext();
			
			$dbContextTask = new DBContextTask(DB_LIKED);
			
			$context->handle("DBContextTask",$dbContextTask);
			
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}
			
			$uid = $task->uid;
			
			if($uid === null){
				$uid = $context->getInternalDataValue("auth");
			}
			
			$item = $dbContext->querySingleEntity("DBLiked","uid={$uid} and etype={$task->etype} and eid={$task->eid}");
			
			if($item){
				if(intval($item->deleted)){
					$item->deleted = 0;
					$item->updateTime = time();
					$dbContext->update($item);
					$task->results = true;
				}
				else{
					$task->results = false;
				}
			}
			else{
				$item = new DBLiked();
				$item->uid = $uid;
				$item->etype = $task->etype;
				$item->eid = $task->eid;
				$item->deleted = 0;
				$item->updateTime = time();
				$item->createTime =time();
				$dbContext->insert($item);
				$task->results = true;
			}
			
			return false;
		}
		
		if($task instanceof LikeCheckTask){
				
			$context = $this->getContext();
			$dbContext = $context->dbContext();
				
			$dbContextTask = new DBContextTask(DB_LIKED);
				
			$context->handle("DBContextTask",$dbContextTask);
				
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}
				
			$uid = $task->uid;
				
			if($uid === null){
				$uid = $context->getInternalDataValue("auth");
			}
				
			$task->results = $dbContext->countForEntity("DBLiked","uid={$uid} and etype={$task->etype} and eid={$task->eid} and (isnull(deleted) or deleted=0)") >0;
				
			return false;
		}
		
		if($taskType == "UnLikeTask"){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext();
			
			$dbContextTask = new DBContextTask(DB_LIKED);
			
			$context->handle("DBContextTask",$dbContextTask);
			
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}
			
			$uid = $task->uid;
			
			if($uid === null){
				$uid = $context->getInternalDataValue("auth");
			}
			
			$item = $dbContext->querySingleEntity("DBLiked","uid={$uid} and etype={$task->etype} and eid={$task->eid}");
			
			if($item){
				if(!intval($item->deleted)){
					$item->deleted = 1;
					$item->updateTime = time();
					$dbContext->update($item);
					$task->results = true;
				}
				else{
					$task->results = false;
				}
			}
			else{
				$task->results = true;
			}
			
			return false;
		}
		
		if($taskType == "LikeRemoveTask"){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext();
				
			$dbContextTask = new DBContextTask(DB_LIKED);
				
			$context->handle("DBContextTask",$dbContextTask);
				
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}
			
			$dbContext->delete("DBLiked","etype={$task->etype} and eid={$task->eid}");
			
			return false;
		}
		
		if($task instanceof LikeQueryTask){
				
			$context = $this->getContext();
			$dbContext = $context->dbContext();
		
			$dbContextTask = new DBContextTask(DB_LIKED);
		
			$context->handle("DBContextTask",$dbContextTask);
		
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}

			$uid = $task->uid;
			
			if($uid === null){
				$uid = $context->getInternalDataValue("auth");
			}
			
			$offset = ($task->pageIndex - 1) * $task->pageSize;
			$limit = $task->pageSize;
			
			$sql = "uid={$uid} and etype={$task->etype} and (isnull(deleted) or deleted=0) ORDER BY updateTime DESC LIMIT {$offset},{$limit}";
			
			$rs = $dbContext->queryEntitys("DBLiked",$sql);
			
			if($rs){
				$task->results = array();
				
				while($item = $dbContext->nextObject($rs, "DBLiked")){
					$task->results[] = $item;
				}
				
				$dbContext->free($rs);
			}
				
			return false;
		}
		
		if($task instanceof LikedCountTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext();
			
			$dbContextTask = new DBContextTask(DB_LIKED);
			
			$context->handle("DBContextTask",$dbContextTask);
			
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}
			
			$uid = $task->uid;
				
			if($uid === null){
				$uid = $context->getInternalDataValue("auth");
			}
			
			$sql = "uid={$uid} AND deleted<>1";
			
			if($task->etypes){
				$sql .= " AND etype IN ".$dbContext->parseArrayValue($task->etypes);
			}
			
			$task->results = $dbContext->countForEntity("DBLiked",$sql);
			
			return false;
		}
		

		if($task instanceof LikeUserQueryTask){
				
			$context = $this->getContext();
			$dbContext = $context->dbContext(DB_LIKED);

			$etype = intval($task->etype);
			$eid = intval($task->eid);
			
			$pageIndex = intval($task->pageIndex);
			
			if($pageIndex <1){
				$pageIndex = 1;
			}
			
			$pageSize = intval($task->pageSize);
			
			if($pageSize <= 0){
				$pageSize = 10;
			}
			
			$offset = ($pageIndex - 1) * $pageSize;
			
			$sql = "etype={$etype} AND eid={$eid} AND deleted<>1 ORDER BY updateTime ASC LIMIT $offset,$pageSize";
				
			$results = array();
				
			$rs = $dbContext->queryEntitys("DBLiked",$sql);

			if($rs){
				while($item = $dbContext->nextObject($rs,"DBLiked")){
					
					$results[] = $item;
					
				}
			}
			
			$task->results = $results;
			
			return false;
		}
		
		return true;
		
	}
	
	
}

?>