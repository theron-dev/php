<?php

/**
 *　权限角色管理服务
 * Tasks : AuthorityRoleAddTask,AuthorityRoleRemoveTask,AuthorityRoleUpdateTask
 * @author zhanghailong
 *
 */
class AuthorityRoleAdminService extends Service{
	
	public function handle($taskType,$task){
		
		if($task instanceof AuthorityRoleAddTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext();
			
			$dbContextTask = new DBContextTask(DB_AUTHORITY);
			
			$context->handle("DBContextTask",$dbContextTask);
			
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}

			if(!$task->name){
				throw new AuthorityException("role name is null",ERROR_AUTHROITY_ROLE_NAME_IS_NULL);
			}
			
			$role = $dbContext->querySingleEntity("DBAuthorityRole","name='{$task->name}'");
			if(!$role){
				$role = new DBAuthorityRole();
				$role->name = $task->name;
				$role->title = $task->title;
				$role->description = $task->description;
				$role->createTime = time();
				$dbContext->insert($role);
			}
			else{
				$log = new AuthorityLogTask(LogLevelInfo,"role {$task->name} is exists");
				$context->handle("LogTask",$log);
				throw new AuthorityException("role {$task->name} is exists",ERROR_AUTHROITY_ROLE_EXISTS);
			}

			$log = new AuthorityLogTask(LogLevelInfo,"role {$task->name} add ok");
			
			$context->handle("LogTask",$log);
			
		}
		
		if($task instanceof AuthorityRoleRemoveTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext();
				
			$dbContextTask = new DBContextTask(DB_AUTHORITY);
				
			$context->handle("DBContextTask",$dbContextTask);
				
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}
			
			
			$dbContext->delete("DBAuthorityRole",array("arid"=>$task->arid));
			
			$log = new AuthorityLogTask(LogLevelInfo,"role {$task->arid} remove ok");
				
			$context->handle("LogTask",$log);
		}
		
		if($task instanceof AuthorityRoleUpdateTask){
				
			$context = $this->getContext();
			$dbContext = $context->dbContext();
				
			$dbContextTask = new DBContextTask(DB_AUTHORITY);
				
			$context->handle("DBContextTask",$dbContextTask);
				
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}
		
			$role = $dbContext->querySingleEntity("DBAuthorityRole","arid={$task->arid}");
			if($role){
				$role->name = $task->name;
				$role->title = $task->title;
				$role->description = $task->description;
				$dbContext->update($role);
			}
			else{
				$log = new AuthorityLogTask(LogLevelInfo,"role {$task->name} not exists");
				$context->handle("LogTask",$log);
				throw new AuthorityException("role {$task->name} not exists",ERROR_AUTHROITY_ROLE_NOT_EXISTS);
			}
		
			$log = new AuthorityLogTask(LogLevelInfo,"role {$task->name} update ok");
				
			$context->handle("LogTask",$log);
				
		}
		
		return true;
	}
}

?>