<?php

/**
 * 标签服务
 * @author zhanghailong
 * @task TagAssignTask , TagMatchTask
 */
class TagService extends Service{
	
	private $tagCache;
	private $tokenizer;
	
	public function __construct(){
		$this->tagCache = array();
	}
	
	public function handle($taskType,$task){
		
		if($task instanceof TagAssignTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext();
				
			$dbContextTask = new DBContextTask(DB_TAG);
				
			$context->handle("DBContextTask",$dbContextTask);
				
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}
			
			$tag = trim($task->tag);
			$item = null;
			
			if(!$tag){
				return false;
			}
			
			if(isset($this->tagCache[$tag])){
				$item = $this->tagCache[$tag];
			}
			
			if($item === null){
				$item = $dbContext->querySingleEntity("DBTag","tag='{$tag}'");
			}
			
			if($item === null){
				$item = new DBTag();
				$item->tag = $tag;
				$item->weight = $task->inc;
				$item->updateTime = time();
				$item->createTime = time();
				$dbContext->insert($item);
			}
			else if($task->inc != 0){
				$item->weight = intval($item->weight) + $task->inc;
				$item->updateTime = time();
				$dbContext->update($item);
			}
			
			$task->tid = $item->tid;
			
			$this->tagCache[$item->tag] = $item;
			
			return false;
		}
		
		if($task instanceof TagMatchTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext();
				
			$dbContextTask = new DBContextTask(DB_TAG);
				
			$context->handle("DBContextTask",$dbContextTask);
				
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}
			
			$config = $this->getConfig();
			
			if(class_exists("XSTokenizerScws")){
				$task->results = array();
				if(!$this->tokenizer){
					$this->tokenizer = new XSTokenizerScws();
					$this->tokenizer->setCharset("utf8");
				}
				
				try{
					$words = $this->tokenizer->getResult($task->body);
					foreach($words as $word){
						$t = new TagAssignTask();
						$t->tag = $word["word"];
						$context->handle("TagAssignTask",$t);
						if($t->tid){
							$task->results[] = $t->tid;
						}
					}
				}
				catch(Exception $ex){
					$rs = $dbContext->queryEntitys("DBTag","'{$task->body}' LIKE CONCAT('%',tag,'%')");
					
					if($rs){
						while($tag = $dbContext->nextObject($rs,"DBTag")){
							$task->results[] = $tag->tid;
							if($task->inc){
								$tag->weight = intval($tag->weight) + $task->inc;
								$tag->updateTime = time();
								$dbContext->update($tag);
							}
						}
						$dbContext->free($rs);
					}
				}
			}
			else{
				$task->results = array();
				
				$rs = $dbContext->queryEntitys("DBTag","'{$task->body}' LIKE CONCAT('%',tag,'%')");
				
				if($rs){
					while($tag = $dbContext->nextObject($rs,"DBTag")){
						$task->results[] = $tag->tid;
						if($task->inc){
							$tag->weight = intval($tag->weight) + $task->inc;
							$tag->updateTime = time();
							$dbContext->update($tag);
						}
					}
					$dbContext->free($rs);
				}
			}
			
			if(isset($config["output"]) && $config["output"]){
				$context->setOutputDataValue("tag-match-results", $task->results);
			}
			
			return false;
		}
		
		if($task instanceof TagTopTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext(DB_TAG);
		
			$task->results = array();
			
			$limit = intval($task->limit);
			
			$rs = $dbContext->queryEntitys("DBTag","1 ORDER BY weight DESC,tid DESC LIMIT {$limit}");
			
			if($rs){
				
				while($row = $dbContext->nextObject($rs,"DBTag")){
					
					$task->results[] = $row;
				
				}
				
				$dbContext->free($rs);
			}
			return false;
		}
		
		return true;
	}
}

?>