<?php

/**
 * 评论服务
 * @author zhanghailong
 * @task CommentCreateTask , CommentRemoveTask 
 */
class CommentService extends Service{
	
	public function handle($taskType,$task){
		
		if($task instanceof CommentCreateTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext();
			
			$dbContextTask = new DBContextTask(DB_COMMENT);
			
			$context->handle("DBContextTask",$dbContextTask);
			
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}
			
			$uid = $task->uid;
			
			if($uid === null){
				$uid = $context->getInternalDataValue("auth");
			}
			
			$item = new DBComment();
			$item->body = $task->body;
			$item->uid = $uid;
			$item->source = $task->source;	
			$item->etype = $task->etype;
			$item->eid = $task->eid;
			$item->ttype = $task->ttype;
			$item->tid = $task->tid;
			$item->tuid = $task->tuid;;
		
			$item->updateTime = time();
			$item->createTime = time();
			
			$dbContext->insert($item);
			
			$task->results = $item;
			
			if(class_exists("ATUserBodyTask")){
				$t = new ATUserBodyTask();
				$t->body = $task->body;
				$t->etype = $item->etype;
				$t->eid = $item->eid;
				$t->ttype = DBATUserTargetTypeComment;
				$t->tid = $item->cid;
				
				$context->handle("ATUserBodyTask", $t);
			}
			
			return false;
		}
		
		if($task instanceof CommentRemoveTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext();
				
			$dbContextTask = new DBContextTask(DB_COMMENT);
				
			$context->handle("DBContextTask",$dbContextTask);
				
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}
			
			$uid = $task->uid;
			
			if($uid === null){
				$uid = $context->getInternalDataValue("auth");
			}
			
			$item = $dbContext->get("DBComment",array("cid"=>$task->cid));
			
			if($item){
				if($item->uid != $uid){
					$authTask = new AuthorityEntityValidateTask(COMMENT_ADMIN_AUTH_ALIAS);
					$context->handle("AuthorityEntityValidateTask",$authTask);
				}
				$dbContext->delete($item);
				$task->results = true;
				
				if(class_exists("ATUserRemoveTask")){
					
					$t = new ATUserRemoveTask();
					$t->etype = $item->etype;
					$t->eid = $item->eid;
					$t->ttype = DBATUserTargetTypeComment;
					$t->ttype = $item->cid;
					
					$context->handle("ATUserRemoveTask", $t);
				}
			}
			else{
				$task->results = false;
			}
			
			return false;
		}
		
		if($task instanceof CommentQueryTask){
				
			$context = $this->getContext();
			$dbContext = $context->dbContext();
		
			$dbContextTask = new DBContextTask(DB_COMMENT);
		
			$context->handle("DBContextTask",$dbContextTask);
		
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}

			$sql = "1=1";
			
			if($task->uid !== null){
				$sql .= " AND uid={$task->uid}";
			}
			
			if($task->tuid !== null){
				$sql .= " AND (tuid={$task->tuid} OR uid={$task->tuid})";
			}

			if($task->etype !== null){
				$sql .= " AND etype={$task->etype}";
			}
			
			if($task->eid !== null){
				$sql .= " AND eid={$task->eid}";
			}
			
			if($task->ttype !== null){
				$sql .= " AND ttype={$task->ttype}";
			}
				
			if($task->tid !== null){
				$sql .= " AND tid={$task->tid}";
			}
			
			if($task->orderType == "asc"){
				$sql .= " ORDER BY cid ASC";
			}
			else{
				$sql .= " ORDER BY cid DESC";
			}
			
			$pageIndex = intval($task->pageIndex);
			
			if($pageIndex < 1){
				$pageIndex = 1;
			}
			
			$pageSize = intval($task->pageSize);
			
			if($pageSize <= 0){
				$pageSize = 20;
			}
			
			$offset = ( $pageIndex -1) * $pageSize;
			
			$sql .= " LIMIT {$offset},{$pageSize}";
			
			$rs = $dbContext->queryEntitys("DBComment",$sql);

			$task->results = array();
			
			if($rs){
				
				while($item = $dbContext->nextObject($rs, "DBComment")){
					$task->results[] = $item;
				}
				
				$dbContext->free($rs);
			}
			
			return false;
		}
		
		if($task instanceof CommentGetTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext();
			
			$dbContextTask = new DBContextTask(DB_COMMENT);
			
			$context->handle("DBContextTask",$dbContextTask);
			
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}
			
			$task->results = $dbContext->get("DBComment",array("cid"=>$task->cid));
			
			return false;
		}
		
		return true;
	}
	
	
}

?>