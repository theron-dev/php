<?php

/**
 *　发布系统服务
 * Tasks : PublishPutTask,PublishLockTask,PublishUnlockTask
 * @author zhanghailong
 *
 */
class PublishService extends Service{
	
	public function handle($taskType,$task){
		
		if($task instanceof PublishCreateTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext();
				
			$dbContextTask = new DBContextTask(DB_PUBLISH);
				
			$context->handle("DBContextTask",$dbContextTask);
				
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}
				
			$paths = split("/",	 $task->target);
			
			$uid = $context->getInternalDataValue("auth");
			
			if(count($paths) >0){
				
				$domain = $paths[0];
				
				$dom = $dbContext->querySingleEntity("DBPublishDomain","domain='{$domain}'");
				
				if(!$dom){
					$dom = new DBPublishDomain();
					$dom->uid = $uid;
					$dom->domain = $domain;
					$dom->state = DBPublishDomainStateNone;
					$dom->title = $task->title;
					$dom->body = $task->body;
					$dom->updateTime = time();
					$dom->createTime = time();
					$dbContext->insert($dom);
					$task->domain = $dom;
				}
				else{
					$task->domain = $dom;
				}
				
				if(count($paths) >2){
					$path = $paths[1];
					$i = 2;
					while($i<count($paths) -1){
						$path .= "/".$paths[$i];
						$i ++;
					}
					$version = $paths[$i];
					
					$schema = $dbContext->querySingleEntity("DBPublishSchema","pdid={$dom->pdid} AND `path`='{$path}' AND `version`='{$version}'");
					if(!$schema){
						$schema = new DBPublishSchema();
						$schema->uid = $uid;
						$schema->pdid = $dom->pdid;
						$schema->path = $path;
						$schema->version = $version;
						$schema->title = $task->title;
						$schema->body = $task->body;
						$schema->state = DBPublishSchemaStateNone;
						$schema->updateTime = time();
						$schema->createTime = time();
						$dbContext->insert($schema);
						$task->schema = $schema;
					}
					else{
						$task->schema = $schema;
					}
				}
			}
			
			return false;
		}
		
		if($task instanceof PublishCreateVersionTask){
				
			$context = $this->getContext();
			$dbContext = $context->dbContext();
		
			$dbContextTask = new DBContextTask(DB_PUBLISH);
		
			$context->handle("DBContextTask",$dbContextTask);
		
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}
		
			$paths = split("/",	 $task->target);
				
			$uid = $context->getInternalDataValue("auth");
				
			if(count($paths) >2){
		
				$domain = $paths[0];
		
				$dom = $dbContext->querySingleEntity("DBPublishDomain","domain='{$domain}'");
				
				if(!$dom){
					throw new PublishException("not found schema",ERROR_PUBLISH_NOT_FOUND_DOMAIN);
				}
				
				$task->domain = $dom;
				
				$path = $paths[1];
				
				$i = 2;
				while($i<count($paths) -1){
					$path .= "/".$paths[$i];
					$i ++;
				}
				
				$version = $paths[$i];
				
				$schema = $dbContext->querySingleEntity("DBPublishSchema","pdid={$dom->pdid} AND `path`='{$path}' AND `version`='{$version}'");
				
				if($schema){
					
					$psid = $schema;
					
					$schema = $dbContext->querySingleEntity("DBPublishSchema","pdid={$dom->pdid} AND `path`='{$path}' AND `version`=".$dbContext->parseValue($task->version));
					
					if($schema){
						$task->schema = $schema;
					}
					else{
	
						$schema = new DBPublishSchema();
						$schema->uid = $uid;
						$schema->pdid = $dom->pdid;
						$schema->path = $path;
						$schema->version = $task->version;
						$schema->title = $task->title;
						$schema->body = $task->body;
						$schema->state = DBPublishSchemaStateNone;
						$schema->updateTime = time();
						$schema->createTime = time();
						$dbContext->insert($schema);
						
						$task->schema = $schema;
						
						$rs = $dbContext->queryEntitys("DBPublishSchemaEntity","psid={$psid}");
						
						if($rs){
							
							while($entity = $dbContext->nextObject($rs,"DBPublishSchemaEntity")){
								
								$entity->psid = $schema->psid;
								$entity->updateTime = $entity->createTime = time();
								$entity->publishTime = null;
								
								$dbContext->insert($entity);
								
							}
							
							$dbContext->free($rs);
						}
					}
				}
				else{
					throw new PublishException("not found schema",ERROR_PUBLISH_NOT_FOUND_SCHEMA);
				}
				
			}
				
			return false;
		}
		
		if($task instanceof PublishDataAddTask){
			
			$runtime = $this->getRuntime($task->target, $task->dbKey);
			
			$runtime->add($task->data,$task->timestamp);
			
			return false;
		}
		
		if($task instanceof PublishDataRemoveTask){
				
			$runtime = $this->getRuntime($task->target, $task->dbKey);
				
			$runtime->remove($task->eid);
				
			return false;
		}
		
		if($task instanceof PublishDataClearTask){
		
			$runtime = $this->getRuntime($task->target, $task->dbKey);
		
			$runtime->clear();
		
			return false;
		}
		
		if($task instanceof PublishReleaseTask){
			
			$runtime = $this->getRuntime($task->target, $task->dbKey);
			
			global $library;
			
			if($task->sandbox){
				$runtime->releaseTo("$library/org.hailong.publish/sandbox");
			}
			else{
				$runtime->releaseTo("$library/org.hailong.publish/runtime");
			}
			
			return false;
		}
		
		return true;
	}
	
	private $_runtimes;
	
	public function getRuntime($target,$dbKey){
		
		if(isset($this->_runtimes[$target])){
			return $this->_runtimes[$target];
		}
		
		$context = $this->getContext();
		
		$dbContext = $context->dbContext(DB_PUBLISH);
		
		$paths = split("/", $target);
		
		$domain = $paths[0];
		
		$dom = $dbContext->querySingleEntity("DBPublishDomain","domain='{$domain}'");
		
		if(!$dom){
			throw new PublishException("not found schema",ERROR_PUBLISH_NOT_FOUND_DOMAIN);
		}
		
		$uid = $context->getInternalDataValue("auth");
		
		if($dom->uid != $uid){
			
			$t = new AuthorityEntityValidateTask("publish/{$dom->domain}");
			
			$context->handle("AuthorityEntityValidateTask", $t);
			
		}
		
		$path = $paths[1];
		
		$i = 2;
		while($i<count($paths) -1){
			$path .= "/".$paths[$i];
			$i ++;
		}
		
		$version = $paths[$i];
		
		$schema = $dbContext->querySingleEntity("DBPublishSchema","pdid={$dom->pdid} AND `path`='{$path}' AND `version`='{$version}'");
		
		if(!$schema){
			throw new PublishException("not found schema",ERROR_PUBLISH_NOT_FOUND_SCHEMA);
		}
		
		$runtime = new DBPublishSchemaRuntime($context, $context->dbContext($dbKey), $domain, $scheam);
		
		if(!$this->_runtimes){
			$this->_runtimes = array();
		}
		
		$this->_runtimes[$target] = $runtime;
		
		return $runtime;
	}
}

?>