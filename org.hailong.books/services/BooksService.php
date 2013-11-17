<?php

/**
 * Books服务
 * @author zhanghailong
 *
 */
class BooksService extends Service{
	
	public function handle($taskType,$task){
		
		if($task instanceof BooksCreateTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext(DB_BOOKS);
			
			$uid = $context->getInternalDataValue("auth");
			
			$item = new DBBooks();
			$item->uid = $uid;
			
			if($task->body){
				if(is_string($task->body)){
					$item->body = json_encode(array("body"=>$task->body));
				}
				else{
					$item->body = json_encode($task->body);
				}
			}
			
			$item->expendMoney = $task->expendMoney;
			
			if($task->payMoney === null){
				$item->payMoney = $task->expendMoney;
			}
			else{
				$item->payMoney = $task->payMoney;
			}
			
			$item->latitude = $task->latitude;
			$item->longitude = $task->longitude;
			$item->type = $task->type;
			$item->unit = $task->unit;
			$item->updateTime = time();
			$item->createTime = time();
			
			$dbContext->insert($item);
			
			$item->body = json_decode($item->body,true);
			
			$task->results = $item;
			
			return false;
		}
		
		if($task instanceof BooksQueryTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext(DB_BOOKS);
				
			$uid = $context->getInternalDataValue("auth");

			$sql = "uid={$uid}";
			
			if($task->startTime !== null){
				$sql .= " AND createTime>=".intval($task->startTime);
			}
			
			if($task->endTime !== null){
				$sql .= " AND createTime<=".intval($task->endTime);
			}
			
			$sql .= " ORDER BY bid ASC";
			
			$rs = $dbContext->queryEntitys("DBBooks",$sql);
			
			$resutls = array();
			
			if($rs){
				
				while($item = $dbContext->nextObject($rs,"DBBooks")){
	
					if($item->body){
						$item->body = json_decode($item->body,true);
					}
					
					$resutls[] = $item;
				}
				
				$dbContext->free($rs);
			}
			
			$task->results = $resutls;
				
			return false;
		}
		
		return true;
	}
}


?>