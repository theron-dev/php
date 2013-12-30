<?php

/**
 *　发布系统服务
 * Tasks : PublishPutTask,PublishLockTask,PublishUnlockTask
 * @author zhanghailong
 *
 */
class PublishService extends Service{
	
	public function handle($taskType,$task){
	
		if($task instanceof PublishPutTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext();
				
			$dbContextTask = new DBContextTask(DB_PUBLISH);
				
			$context->handle("DBContextTask",$dbContextTask);
				
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}
			
			$targetDBContext = $context->dbContext();
			
			$dbContextTask = new DBContextTask($task->dbKey);
			
			$context->handle("DBContextTask",$dbContextTask);
			
			if($dbContextTask->dbContext){
				$targetDBContext = $dbContextTask->dbContext;
			}
			
			$paths = split("/",	 $task->target);
			
			if(count($paths) >0){
				
				$domain = $paths[0];
				
				$dom = $dbContext->querySingleEntity("DBPublishDomain","domain='{$doamin}'");
				
				if($dom){
					
					$t = new AuthorityEntityValidateTask("org/hailong/publish/{$domain}/put");
					
					$context->handle("AuthorityEntityValidateTask", $t);
					
					$path = null;
					
					if(count($paths) > 1){
						$path = $paths[1];
					}
					
					$version = null;
					
					if(count($paths) > 2){
						$version = $paths[2];
					}
					
					$source = $task->source;
					
					if($path && $version && $source){
				
						$sql = "pdid={$dom->pdid} AND state<>".DBPublishSchemaStateDisabled." AND path='{$path}' AND version='$version'";
						
						$rs = $dbContext->queryEntitys("DBPublishSchema",$sql);
						
						global $library;
						
						$xsltDir = "$library/org.hailong.publish/xslt";
						
						if($rs){
							
							while($schema = $dbContext->nextObject($rs, "DBPublishSchema")){
								
								$dir = $task->dir."/".$domain."/".$schema->path."/".$scheam->version;
								
								mkdir($dir,0777,true);
									
								$runtime = new DBPublishSchemaRuntime($context, $targetDBContext, $dom->domain, $scheam);
								
								$xslt = file_get_contents("$xsltDir/query.xsl");
								
								if($xslt){
									$xml = $runtime->xslt($xslt);
									file_put_contents("$dir/query.xml", $xml);
								}
								
								$c = $source->publishDataSourceCount();
								
								for($i=0;$i<$c;$i++){
									$data = $source->publishDataSourceData($i);
									$sid = $source->publishDataSourceId($data);
									$timestamp = $source->publishDataSourceTimestamp($data);
									if($data && $sid && $timestamp){
										$runtime->put($data, $sid, $timestamp);
									}
								}
	
							}
							
							$dbContext->free($rs);
						}
					}
				}
			}
			
			return false;
		}
		
		if($task instanceof PublishLockTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext();
			
			$dbContextTask = new DBContextTask(DB_PUBLISH);
			
			$context->handle("DBContextTask",$dbContextTask);
			
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}
			
			$paths = split("/",	 $task->target);
				
			if(count($paths) >0){
			
				$domain = $paths[0];
			
				$dom = $dbContext->querySingleEntity("DBPublishDomain","domain='{$doamin}'");
			
				if($dom){
						
					$t = new AuthorityEntityValidateTask("org/hailong/publish/{$domain}/put");
						
					$context->handle("AuthorityEntityValidateTask", $t);
						
					$path = null;
						
					if(count($paths) > 1){
						$path = $paths[1];
					}
						
					$version = null;
						
					if(count($paths) > 2){
						$version = $paths[2];
					}
					
					$sql = "pdid={$dom->pdid} AND state<>".DBPublishSchemaStateDisabled;
						
					if($path){
						$sql .=" AND path='$path'";
					}
						
					if($version){
						$sql .=" AND version='$version'";
					}
					
					$rs = $dbContext->queryEntitys("DBPublishSchema",$sql);
						
					if($rs){

						while($schema = $dbContext->nextObject($rs, "DBPublishSchema")){
								
							$p = $domain."/".$schema->path."/".$scheam->version;
							$dir = $this->dir."/".$p;
							$lock =  "$dir/.lock";
							
							if(file_exists($lock)){
								throw new PublishException($p." is locked", ERROR_PUBLISH_SCHEMA_IS_LOCK);
							}
							else{
								file_put_contents($lock, $p);
							}
					
						}
					
						$dbContext->free($rs);
					}

				}
			}
		}
		
		if($task instanceof PublishUnlockTask){
				
			$context = $this->getContext();
			$dbContext = $context->dbContext();
			
			$dbContextTask = new DBContextTask(DB_PUBLISH);
			
			$context->handle("DBContextTask",$dbContextTask);
			
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}
			
			$paths = split("/",	 $task->target);
		
			if(count($paths) >0){
					
				$domain = $paths[0];
					
				$dom = $dbContext->querySingleEntity("DBPublishDomain","domain='{$doamin}'");
					
				if($dom){
		
					$t = new AuthorityEntityValidateTask("org/hailong/publish/{$domain}/put");
		
					$context->handle("AuthorityEntityValidateTask", $t);
		
					$path = null;
		
					if(count($paths) > 1){
						$path = $paths[1];
					}
		
					$version = null;
		
					if(count($paths) > 2){
						$version = $paths[2];
					}
						
					$sql = "pdid={$dom->pdid} AND state<>".DBPublishSchemaStateDisabled;
		
					if($path){
						$sql .=" AND path='$path'";
					}
		
					if($version){
						$sql .=" AND version='$version'";
					}
						
					$rs = $dbContext->queryEntitys("DBPublishSchema",$sql);
		
					if($rs){
		
						while($schema = $dbContext->nextObject($rs, "DBPublishSchema")){
		
							$p = $domain."/".$schema->path."/".$scheam->version;
							$dir = $this->dir."/".$p;
							$lock =  "$dir/.lock";
								
							unlink($lock);
						}
							
						$dbContext->free($rs);
					}
		
				}
			}
			
			return false;
		}
		
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
					
					$schema = $dbContext->querySingleEntity("DBPublishSchema","pdid={$dom->pdid} AND `path`=`{$path}` AND `version`=`{$version}`");
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
		
		
		return true;
	}
	
}

?>