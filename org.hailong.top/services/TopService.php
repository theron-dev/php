<?php

/**
 * 热门服务
 * @author zhanghailong
 *
 */
class QDDTopService extends Service{
	
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
			
			$topCounts = array();
			
			$topCounts[$eid] = $topCount;
			
			$timestamp = time() - intval($task->limitTime);
			
			$where = "`key`='{$key}' AND etype={etype} WHERE createTime<{$timestamp}";
			
			$sql = "SELECT eid,SUB(topCount) as topCount FROM `".DBTopItem::tableName()."` WHERE {$where} GROUP BY eid";
			
			$rs = $dbContext->query($sql);
			
			if($rs){
				
				while($row = $dbContext->next($rs)){
					
					$eid = $row["eid"];
					
					if(isset($topCounts[$eid])){
						$topCounts[$eid] +=  $row["topCount"];
					}
					else{
						$topCounts[$eid] = $row["topCount"];
					}
					
				}
				
				$dbContext->free($rs);

			}

			$dbContext->delete("DBTopItem",$where);
			
			foreach ($topCounts as $eid => $topCount){
				$dbContext->query("UPDATE `".DBTop::tableName()."` SET topCount=topCount+{$topCount} WHERE `key`='{$key}' AND etype={$etype} AND eid={$eid}");
			}
			
			return false;
		}
		
		return true;
	}
	
	
}

?>