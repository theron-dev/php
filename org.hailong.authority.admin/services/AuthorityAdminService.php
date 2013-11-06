<?php

/**
 *　权限管理服务
 * Tasks : AuthorityAddTask,AuthorityRemoveTask
 * @author zhanghailong
 *
 */
class AuthorityAdminService extends Service{
	
	public function handle($taskType,$task){
		
		if($task instanceof AuthorityAddTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext();
			
			$dbContextTask = new DBContextTask(DB_AUTHORITY);
			
			$context->handle("DBContextTask",$dbContextTask);
			
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}

			if(!$task->arid && !$task->aeid){
				throw new AuthorityException("authority not found role or alias",ERROR_AUTHROITY_NOT_FOUND_ROLE_OR_ALIAS);
			}
			
			$sql = "tid={$task->tid} and ttype={$task->ttype}";
			
			if($task->arid){
				$sql .= " and arid={$task->arid}";
			}
			else{
				$sql .= " and aeid={$task->aeid}";
			}
			
			$auth = $dbContext->querySingleEntity("DBAuthority",$sql);
			if(!$auth){
				$auth = new DBAuthority();
				$auth->tid = $task->tid;
				$auth->ttype = $task->ttype;
				if($task->arid){
					$auth->arid = $task->arid;
				}
				else{
					$auth->aeid = $task->aeid;
				}
				$auth->createTime = time();
				$dbContext->insert($auth);
			}
			
			$task->aid = $auth->aid;

			$log = new AuthorityLogTask(LogLevelInfo,"authority tid:{$task->tid} ttype:{$task->ttype} arid:{$task->arid} aeid:{$task->aeid} add ok");
			
			$context->handle("LogTask",$log);
			
		}
		
		if($task instanceof AuthorityRemoveTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext();
				
			$dbContextTask = new DBContextTask(DB_AUTHORITY);
				
			$context->handle("DBContextTask",$dbContextTask);
				
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}
			
			if(!$task->arid && !$task->aeid){
				throw new AuthorityException("authority not found role or alias",ERROR_AUTHROITY_NOT_FOUND_ROLE_OR_ALIAS);
			}
				
			$sql = "tid={$task->tid} and ttype={$task->ttype}";
				
			if($task->arid){
				$sql .= " and arid={$task->arid}";
			}
			else{
				$sql .= " and aeid={$task->aeid}";
			}
			
			$dbContext->delete("DBAuthority",$sql);
			
			$log = new AuthorityLogTask(LogLevelInfo,"authority tid:{$task->tid} ttype:{$task->ttype} arid:{$task->arid} aeid:{$task->aeid} remove ok");
				
			$context->handle("LogTask",$log);
		}
		
		return true;
	}
}

?>