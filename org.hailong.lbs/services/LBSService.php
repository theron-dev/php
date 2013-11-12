<?php

/**
 * LBS服务
 * @author zhanghailong
 *
 */
class LBSService extends Service{
	
	public function handle($taskType,$task){
		
		if($task instanceof LBSDistanceTask){
			
			$radLat1 = $task->latitude1 * pi() / 180.0;
			$radLat2 = $task->latitude2 * pi() / 180.0;
			$a = $task->latitude1 - $task->latitude2;
			$b = ($task->longitude1 * pi() / 180.0) - ($task->longitude2 * pi() / 180.0);
			$s = 2.0 * asin(sqrt(pow(sin($a/2.0),2.0) +
				cos($radLat1) * cos($radLat2) * pow(sin($b/2.0),2.0)));
			
			$s = $s * 6378.137;
			
			$task->distance = $s * 1000;
			
			return false;
		}
		
		if($task instanceof LBSSourceUpdateTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext(DB_LBS);
			
			$sid = intval($task->sid);
			$stype = intval($task->stype);
			$latitude = doubleval($task->latitude);
			$longitude = doubleval($task->longitude);
			$item = $dbContext->querySingleEntity("DBLBSSource","sid=$sid AND stype=$stype");
			
			if($item){
				if(doubleval($item->latitude) != $latitude
					|| doubleval($item->longitude) != $longitude){
					$item->latitude = $task->latitude;
					$item->longitude = $task->longitude;
					$item->updateTime = time();
				}
				$dbContext->update($item);
			}
			else{
				$item = new DBLBSSource();
				$item->stype = $stype;
				$item->sid = intval($task->sid);
				$item->latitude = doubleval($task->latitude);
				$item->longitude = doubleval($task->longitude);
				$item->createTime = time();
				$item->updateTime = time();
				$dbContext->insert($item);
			}
		
			$dbContext->commit();
			
		}
		
		if($task instanceof LBSSourceRemoveTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext(DB_LBS);
				
			$sid = intval($task->sid);
			$stype = intval($task->stype);
			
			$dbContext->delete("DBLBSSource","sid=$sid AND stype=$stype");
			$dbContext->delete("DBLBSSearch","sid=$sid AND stype=$stype");
			$dbContext->commit();
			
		}
		
		if($task instanceof LBSSearchTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext(DB_LBS);
			
			$sid = intval($task->sid);
			$stype = intval($task->stype);
			$latitude = doubleval($task->latitude);
			$longitude = doubleval($task->longitude);
			$distance = intval($task->distance);
			
			if(!$distance){
				$distance = 1000;
			}
			
			$item = $dbContext->querySingleEntity("DBLBSSource","sid=$sid AND stype=$stype");
			
			if($item){
				
				if($item->updateTime != $item->searchTime){
				
					$dbContext->delete("DBLBSSearch","sid=$sid AND stype=$stype");
					$dbContext->commit();
					
					$task->onUpateSource($context, $dbContext);
					
					$item->searchTime = $item->updateTime;
					
					$dbContext->update($item);
				}
				
				$pageIndex = intval($task->pageIndex);
				$pageSize = intval($task->pageSize);
				
				if($pageIndex < 1){
					$pageIndex = 1;
				}
				
				if($pageSize <= 0){
					$pageSize = 20;
				}
				
				$offset = ($pageIndex - 1) * $pageSize;
				
				$sql = "SELECT * FROM ".DBLBSSearch::tableName()." WHERE sid=$sid AND stype=$stype ORDER BY distance DESC LIMIT $offset,$pageSize";
				
				$rs = $dbContext->query($sql);
				
				$task->results = array();
				
				if($rs){
				
					while($row = $dbContext->nextObject($rs,"DBLBSSearch")){
						$task->results[] = $row;
					}
				
					$dbContext->free($rs);
				}
				
			}
			
		}
		
		
		return true;
	}
}

?>