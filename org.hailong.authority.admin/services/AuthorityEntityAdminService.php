<?php

/**
 *　权限实体管理服务
 * Tasks : AuthorityEntityAddTask,AuthorityEntityRemoveTask,AuthorityEntityUpdateTask
 * @author zhanghailong
 *
 */
class AuthorityEntityAdminService extends Service{
	
	public function handle($taskType,$task){
		
		if($task instanceof AuthorityEntityAddTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext();
			
			$dbContextTask = new DBContextTask(DB_AUTHORITY);
			
			$context->handle("DBContextTask",$dbContextTask);
			
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}

			if(!$task->alias){
				throw new AuthorityException("authroity entity alias is null",ERROR_AUTHROITY_ENTITY_ALIAS_IS_NULL);
			}
			
			$entity = $dbContext->querySingleEntity("DBAuthorityEntity","alias='{$task->alias}'");
			if(!$entity){
				$entity = new DBAuthorityEntity();
				$entity->alias = $task->alias;
				$entity->title = $task->title;
				$entity->createTime = time();
				$dbContext->insert($entity);
			}
			else{
				$log = new AuthorityLogTask(LogLevelInfo,"authroity entity {$task->alias} is exists");
				$context->handle("LogTask",$log);
				throw new AuthorityException("authroity entity {$task->alias} is exists",ERROR_AUTHROITY_ENTITY_EXISTS);
			}

			$log = new AuthorityLogTask(LogLevelInfo,"authroity entity {$task->alias} add ok");
			
			$context->handle("LogTask",$log);
			
		}
		
		if($task instanceof AuthorityEntityRemoveTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext();
				
			$dbContextTask = new DBContextTask(DB_AUTHORITY);
				
			$context->handle("DBContextTask",$dbContextTask);
				
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}
			
			$dbContext->delete("DBAuthorityEntity",array("aeid"=>$task->aeid));
			
			$log = new AuthorityLogTask(LogLevelInfo,"authroity entity {$task->aeid} remove ok");
				
			$context->handle("LogTask",$log);
		}
		
		if($task instanceof AuthorityEntityUpdateTask){
				
			$context = $this->getContext();
			$dbContext = $context->dbContext();
				
			$dbContextTask = new DBContextTask(DB_AUTHORITY);
				
			$context->handle("DBContextTask",$dbContextTask);
				
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}
		
			$entity = $dbContext->querySingleEntity("DBAuthorityEntity","aeid={$task->aeid}");
			if($entity){
				$entity->alias = $task->alias;
				$entity->title = $task->title;
				$dbContext->update($entity);
			}
			else{
				$log = new AuthorityLogTask(LogLevelInfo,"authority entity {$task->alias} not exists");
				$context->handle("LogTask",$log);
				throw new AuthorityException("authority entity {$task->alias} not exists",ERROR_AUTHROITY_ENTITY_NOT_EXISTS);
			}
		
			$log = new AuthorityLogTask(LogLevelInfo,"authority entity {$task->alias} update ok");
				
			$context->handle("LogTask",$log);
				
		}
		
		return true;
	}
}

?>