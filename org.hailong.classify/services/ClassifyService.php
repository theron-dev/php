<?php

/**
 * 分类服务
 * @author zhanghailong
 * @task ClassifyCreateTask , ClassifyRemoveTask , ClassifyUpdateTask , ClassifyQueryTask , ClassifyMatchTask
 */
class ClassifyService extends Service{
	
	public function handle($taskType,$task){
		
		if($taskType == "ClassifyCreateTask"){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext();
				
			$dbContextTask = new DBContextTask(DB_CLASSIFY);
				
			$context->handle("DBContextTask",$dbContextTask);
				
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}
			
			$item = new DBClassify();
			$item->pcid = $task->pcid;
			$item->title = $task->title;
			$item->target = $task->target;
			$item->deleted = 0;
			$item->updateTime = time();
			$item->createTime = time();
			
			$dbContext->insert($item);
			
			$task->cid = $item->cid;
			
			if($task->keyword){
				$tags = preg_split("/[\, ;\/]/i", $task->keyword);
			
				foreach($tags as $tag){
					
					$tag = trim($tag);
					
					if($tag){
						
						$tagTask = new TagAssignTask();
						$tagTask->tag  = $tag;
						
						$context->handle("TagAssignTask",$tagTask);
						
						if($tagTask->tid){
							$kItem = new DBClassifyKeyword();
							$kItem->cid = $item->cid;
							$kItem->tid = $tagTask->tid;
							$kItem->createTime = time();
							$dbContext->insert($kItem);
						}
					}
				}
			}
			
			return false;
		}
		
		if($taskType == "ClassifyRemoveTask"){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext();
			
			$dbContextTask = new DBContextTask(DB_CLASSIFY);
			
			$context->handle("DBContextTask",$dbContextTask);
			
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}

			$item = $dbContext->get("DBClassify",array("cid"=>$task->cid));
			
			if($item){
				if( intval($item->deleted) != 1){
					$item->deleted = 1;
					$item->updateTime = time();
					$dbContext->update($item);
					
					$dbContext->delete("DBClassifyKeyword","cid={$item->cid}");
				}
			}
			
			return false;
		}
		
		if($taskType == "ClassifyUpdateTask"){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext();
				
			$dbContextTask = new DBContextTask(DB_CLASSIFY);
				
			$context->handle("DBContextTask",$dbContextTask);
				
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}
			
			$item = $dbContext->get("DBClassify",array("cid"=>$task->cid));
			
			if($item && intval($item->deleted) ==0){
				
				$needUpdate = false;
				
				if($task->pcid !== null && $item->pcid != $task->pcid){
					$item->pcid = $task->pcid;
					$needUpdate = true;
				}
				
				if($task->title !== null && $item->title != $task->title){
					$item->title = $task->title;
					$needUpdate = true;
				}
				
				if($task->logo !== null && $item->logo != $task->logo){
					$item->logo = $task->logo;
					$needUpdate = true;
				}
				
				if($task->keyword !== null){
					
					$dbContext->delete("DBClassifyKeyword","cid={$item->cid}");
					
					$tags = preg_split("/[\, ;\/]/i", $task->keyword);
						
					foreach($tags as $tag){
						$tagTask = new TagAssignTask();
						$tagTask->tag  = $tag;
							
						$context->handle("TagAssignTask",$tagTask);
							
						if($tagTask->tid){
							$kItem = new DBClassifyKeyword();
							$kItem->cid = $item->cid;
							$kItem->tid = $tagTask->tid;
							$kItem->weight = 0;
							$kItem->createTime = time();
							$dbContext->insert($kItem);
						}
					}
					
					$needUpdate = true;
				}
				
				$item->updateTime = time();
				$dbContext->update($item);
				
			}
			
			return false;
		}
		
		if($taskType == "ClassifyQueryTask"){
				
			$context = $this->getContext();
			$dbContext = $context->dbContext();
		
			$dbContextTask = new DBContextTask(DB_CLASSIFY);
		
			$context->handle("DBContextTask",$dbContextTask);
		
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}
			
			$sql = "SELECT c.cid as cid,MIN(c.pcid) as pcid,MIN(c.title) as title,MIN(c.logo) as logo,GROUP_CONCAT(t.tag separator ',') as keyword "
				." FROM ".DBClassify::tableName()." as c LEFT JOIN ".DBClassifyKeyword::tableName()." as ck ON c.cid = ck.cid LEFT JOIN ".DBTag::tableName()." as t ON ck.tid=t.tid"
				." WHERE c.pcid={$task->pcid} and c.deleted<>1 and c.target={$task->target}"
				." GROUP BY c.cid ORDER BY c.cid ASC";

			$rs = $dbContext->query($sql);
			
			if($rs){
				$task->results = array();
				
				while($item = $dbContext->next($rs)){
					$task->results[] = $item;
				}

				$dbContext->free($rs);
			}
			
				
			return false;
		}
		
		if($taskType == "ClassifyQueryTopTask"){
		
			$context = $this->getContext();
			$dbContext = $context->dbContext();
		
			$dbContextTask = new DBContextTask(DB_CLASSIFY);
		
			$context->handle("DBContextTask",$dbContextTask);
		
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}
			
			$pcid = intval($task->pcid);
			$target = $dbContext->parseValue($task->target);
			$top = intval($task->top);
				
			$rs = $dbContext->queryEntitys("DBClassify","pcid={$pcid} AND target={$target} AND deleted!=1 ORDER BY cid ASC");
			
			if($rs){
				$task->results = array();
		
				while($classify = $dbContext->nextObject($rs,"DBClassify")){
					
					$item = array();
					
					foreach($classify as $key =>$value){
						if($value !== null){
							$item[$key] = $value;
						}
					}
					
					$cid = $classify->cid;
					
					$sql = "SELECT c.tid as tid,t.tag as tag FROM ".DBClassifyKeyword::tableName()." as c LEFT JOIN ".DBTag::tableName()." as t ON c.tid=t.tid WHERE c.cid={$cid} ORDER BY c.weight DESC LIMIT {$top}";
					
					$rrs = $dbContext->query($sql);
	
					if($rrs){
						
						$tags = array();
						
						while($tag = $dbContext->next($rrs)){
							$tags[] = array("tid"=>$tag["tid"],"tag"=>$tag["tag"]);
						}
						
						$item["tags"] = $tags;
						
						$dbContext->free($rrs);
					}
					$task->results[] = $item;
				}
		
				$dbContext->free($rs);
				
				
					
			}
				
		
			return false;
		}
		
		if($task instanceof ClassifyMatchTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext();
			
			$dbContextTask = new DBContextTask(DB_CLASSIFY);
			
			$context->handle("DBContextTask",$dbContextTask);
			
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}
			
			$t = new TagMatchTask();
			$t->body = $task->body;
			
			$context->handle("TagMatchTask",$t);
			
			if($t->results){
			
				$task->results = array();
				
				ClassifyService::matchClassify($context, $dbContext, $task->target, $dbContext->parseArrayValue($t->results), 0, $task);
			}
			
			return false;
		}
		
		if($taskType == "ClassifyParentTask"){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext();
				
			$dbContextTask = new DBContextTask(DB_CLASSIFY);
				
			$context->handle("DBContextTask",$dbContextTask);
				
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}
			
			$task->results = array();
			
			$item = $dbContext->get("DBClassify",array("cid"=>$task->cid));
			
			while($item){
				$task->results[] = $item;
				if($item->pcid){
					$item = $dbContext->get("DBClassify",array("cid"=>$item->pcid));
				}
				else{
					break;
				}
			}
			
		}
		
		if($taskType == "ClassifyChildTask"){
				
			$context = $this->getContext();
			$dbContext = $context->dbContext();
		
			$dbContextTask = new DBContextTask(DB_CLASSIFY);
		
			$context->handle("DBContextTask",$dbContextTask);
		
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}
				
			$task->results = array();

			$item = $dbContext->get("DBClassify",array("cid"=>$task->cid));

			ClassifyService::queryChild($context, $dbContext, $item, $task);
				
		}
		
		if($taskType == "ClassifyKeywordAssignTask"){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext(DB_CLASSIFY);
			
			$cid = intval($task->cid);
			$tid = intval($task->tid);
			
			$item = $dbContext->querySingleEntity("DBClassifyKeyword","cid=$cid AND tid=$tid");
			
			if($item){
				$item->weight += $task->inc;
				$dbContext->update($item);
			}
			else{
				$item = new DBClassifyKeyword();
				$item->cid = $cid;
				$item->tid = $tid;
				$item->weight = $task->inc;
				$item->createTime = time();
				$dbContext->insert($item);
			}
			
			return false;
		}
		
		return true;
	}
	
	public static function queryChild($context,$dbContext,$item,$task){
		if($item){
			$task->results[] = $item;
			$rs = $dbContext->queryEntitys("DBClassify","pcid={$item->cid}");
			if($rs){
				while($item = $dbContext->nextObject($rs,"DBClassify")){
					ClassifyService::queryChild($context, $dbContext, $item, $task);
				}
				$dbContext->free($rs);
			}
		}
	}
	
	public static function matchClassify($context,$dbContext,$target,$tidsSql,$pcid,$task){
		
		$sql = "SELECT c.cid as cid,count(ck.tid) as tcount FROM ".DBClassifyKeyword::tableName()." as ck LEFT JOIN ".DBClassify::tableName()
			." as c ON ck.cid=c.cid WHERE tid IN ".$tidsSql." AND c.target={$target} AND pcid={$pcid}";
		
		$sql .= " GROUP BY cid ORDER BY tcount DESC";
		
		$rs = $dbContext->query($sql);
		
		$cids = array();
		
		if($rs){	
			while($row = $dbContext->next($rs)){
				$task->results[] = $row["cid"];
				$cids[] = $row["cid"];
			}
			$dbContext->free($rs);
		}
		
		foreach($cids as $cid){
			ClassifyService::matchClassify($context, $dbContext, $target, $tidsSql, $cid, $task);
		}
	}
}

?>