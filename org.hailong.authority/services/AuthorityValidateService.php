<?php

/**
 *　权限验证服务
 * Tasks : AuthorityValidateTask
 * @author zhanghailong
 *
 */
class AuthorityValidateService extends Service{
	
	public function handle($taskType,$task){
		
		if($task instanceof AuthorityRoleValidateTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext();
			
			$dbContextTask = new DBContextTask(DB_AUTHORITY);
			
			$context->handle("DBContextTask",$dbContextTask);
			
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}
			
			if(!$task->uid){
				$task->uid = $context->getInternalDataValue("auth");
			}
			
			if(!$task->valid_role){
			
				$log = new AuthorityLogTask(LogLevelInfo,"authority role is null");
					
				$context->handle("LogTask",$log);
			
				throw new AuthorityException("authority role is null", ERROR_AUTHROITY_ROLE_NAME_IS_NULL);
			}
			
			if(AuthorityRoleAnonymous != $task->valid_role){
				$role = $dbContext->querySingleEntity("DBAuthorityRole","name='{$task->valid_role}'");
				if(!$role){
					$role = new DBAuthorityRole();
					$role->name = $task->valid_role;
					$role->title = $task->valid_role;
					$role->description = "";
					$role->createTime = time();
					$dbContext->insert($role);
				}
				
				$auth = $dbContext->querySingleEntity("DBAuthority","tid={$task->uid} and ttype=".DBAuthorityTargetTypeUser." and arid={$role->arid}");
				if(!$auth){
					$log = new AuthorityLogTask(LogLevelInfo,"user:{$task->uid} in {$task->valid_role} validate invalid");
					$context->handle("LogTask",$log);
					
					throw new AuthorityException("user:{$task->uid} in {$task->valid_role} role validate invalid", ERROR_AUTHROITY_INVALID);
				}
				
			}
			
			$log = new AuthorityLogTask(LogLevelInfo,"user:{$task->uid} in {$task->valid_role} role validate ok");
			
			$context->handle("LogTask",$log);
			
		}
		
		if($task instanceof AuthorityEntityValidateTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext();
				
			$dbContextTask = new DBContextTask(DB_AUTHORITY);
				
			$context->handle("DBContextTask",$dbContextTask);
				
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}
			
			if(!$task->uid){
				$task->uid = $context->getInternalDataValue("auth");
			}
			
			if(!$task->valid_alias){
				
				$log = new AuthorityLogTask(LogLevelInfo,"authority entity alias is null");
					
				$context->handle("LogTask",$log);
				
				throw new AuthorityException("authority entity alias is null", ERROR_AUTHROITY_ENTITY_ALIAS_IS_NULL);
			}
			
			$entity = $dbContext->querySingleEntity("DBAuthorityEntity","alias='{$task->valid_alias}'");
			
			if(!$entity){
				$entity = new DBAuthorityEntity();
				$entity->alias = $task->valid_alias;
				$entity->title = "";
				$entity->createTime = time();
				$dbContext->insert($entity);
			}
			
			$auth = $dbContext->querySingleEntity("DBAuthority","tid={$task->uid} and ttype=".DBAuthorityTargetTypeUser." and aeid={$entity->aeid}");
			
			if(!$auth){
				
				$exception = new AuthorityException("user:{$task->uid} in {$task->valid_alias} entity validate invalid", ERROR_AUTHROITY_INVALID);
				
				$rs = $dbContext->queryEntitys("DBAuthority","tid={$task->uid} and ttype=".DBAuthorityTargetTypeUser." and NOT ISNULL(arid)");
				
				if($rs){
					
					while($auth = $dbContext->nextObject($rs,"DBAuthority")){
						if($dbContext->countForEntity("DBAuthority","tid={$auth->arid} and ttype=".DBAuthorityTargetTypeRole." and aeid='{$entity->aeid}'")){
							$exception = null;
							break;
						}

					}
					
					$dbContext->free($rs);
				}
				
				if($exception){
					
					$log = new AuthorityLogTask(LogLevelInfo,$exception->getMessage());
					
					$context->handle("LogTask",$log);
					
					throw $exception;
				}
			}
			
			$log = new AuthorityLogTask(LogLevelInfo,"user:{$task->uid} in {$task->valid_alias} entity validate ok");
				
			$context->handle("LogTask",$log);
		}
		
		return true;
	}
}

?>