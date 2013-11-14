<?php

/**
 * 热门服务
 * @author zhanghailong
 *
 */
class TopService extends Service{
	
	public function handle($taskType,$task){
		
		if($task instanceof TopItemTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext(DB_TOP);

			$key = trim($task->key);
			$eid = intval($task->eid);
			$etype = intval($task->etype);
			$topCount = intval($task->topCount);
			
			$item = new DBTopItem();
			
			$item->key = $key;
			$item->etype = $etype;
			$item->eid = $eid;
			$item->topCount = $topCount;
			$item->createTime = time();
			
			$dbContext->insert($item);
			
			$item = $dbContext->querySingleEntity("DBTop","`key`='{$key}' AND etype={$etype} AND eid={$eid}");
			
			if($item){
				
				$item->updateTime = time();
				$dbContext->update($item);
				
			}
			else{

				$item = new DBTop();
				
				$item->key = $key;
				$item->etype = $etype;
				$item->eid = $eid;
				$item->topCount = 0;
				$item->createTime = time();
				$item->updateTime = time();
				
				$dbContext->insert($item);
				
				$dbContext->commit();
			}
			
			$topCounts = array();
			
			$topCounts[$eid] = $topCount;
			
			if(!$task->limitTime){
				$task->limitTime = time();
			}
			
			$timestamp = time() - intval($task->limitTime);
			
			$where = "`key`='{$key}' AND etype={etype} WHERE createTime<{$timestamp}";
			
			$sql = "SELECT eid,SUM(topCount) as topCount FROM `".DBTopItem::tableName()."` WHERE {$where} GROUP BY eid";
			
			$rs = $dbContext->query($sql);
			
			if($rs){
				
				while($row = $dbContext->next($rs)){
					
					$eid = $row["eid"];
					
					if(isset($topCounts[$eid])){
						$topCounts[$eid] -=  $row["topCount"];
					}
					else{
						$topCounts[$eid] = - $row["topCount"];
					}
					
				}
				
				$dbContext->free($rs);

			}

			$dbContext->delete("DBTopItem",$where);
			
			foreach ($topCounts as $eid => $topCount){
				$dbContext->query("UPDATE `".DBTop::tableName()."` SET topCount=topCount+{$topCount},updateTime=".time()." WHERE `key`='{$key}' AND etype={$etype} AND eid={$eid}");
			}
			
			return false;
		}
		else if($task instanceof TopRemoveTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext(DB_TOP);
			
			$eid = intval($task->eid);
			$etype = intval($task->etype);

			$dbContext->query("DELETE FROM `".DBTopItem::tableName()."` WHERE etype={$etype} AND eid={$eid}");
			$dbContext->query("DELETE FROM `".DBTop::tableName()."` WHERE etype={$etype} AND eid={$eid}");
			
			return false;
		}
		else if($task instanceof TopSearchTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext(DB_TOP);
				
			$key = trim($task->key);
			$etype = intval($task->etype);
				
			$pageIndex = intval($task->pageIndex);
			
			if($pageIndex < 1){
				$pageIndex = 1;
			}
			
			$pageSize = intval($task->pageSize);
			
			if($pageSize < 1){
				$pageSize = 20;
			}
			
			$offset = ($pageIndex - 1) * $pageSize;
			
			$rs = $dbContext->queryEntitys("DBTop","`key`='{$key}' AND etype={$etype} ORDER BY topCount DESC,updateTime DESC LIMIT {$offset},{$pageSize}");
			
			if($rs){
				
				$task->results = array();
				
				while($item  = $dbContext->nextObject($rs,"DBTop")){
					
					$task->results[] = $item;
					
				}
				
				$dbContext->free($rs);
				
			}
			
			return false;
		}
		
		return true;
	}
	
	
}

?>