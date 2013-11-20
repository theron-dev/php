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
			
			if($task->pcid !== null){
				$pItem = $dbContext->get("DBComment",array("cid"=>$task->pcid));
				if($pItem){
					$item->pcid = $task->pcid;
					$item->etype = $pItem->etype;
					$item->eid = $pItem->eid;
				}
				else{
					throw new CommentException("ERROR_PARENT_COMMENT_NOT_FOUND", ERROR_PARENT_COMMENT_NOT_FOUND);
				}
			}
			else{
				$item->etype = $task->etype;
				$item->eid = $task->eid;
				$item->pcid = 0;
			}
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
		
		if($task instanceof CommentListTask){
				
			$context = $this->getContext();
			$dbContext = $context->dbContext();
		
			$dbContextTask = new DBContextTask(DB_COMMENT);
		
			$context->handle("DBContextTask",$dbContextTask);
		
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}
				
			$sql = "etype={$task->etype} AND eid={$task->eid} AND pcid={$task->pcid} ORDER BY cid DESC";
			
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