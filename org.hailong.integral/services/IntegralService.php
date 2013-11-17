<?php

/**
 * 积分服务
 * @author zhanghailong
 * @task IntegralIncomeTask , IntegralTotalTask
 */
class IntegralService extends Service{
	
	public function handle($taskType,$task){
		
		if($task instanceof IntegralIncomeTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext();
			
			$dbContextTask = new DBContextTask(DB_INTEGRAL);
			
			$context->handle("DBContextTask",$dbContextTask);
			
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}
			
			$uid = $task->uid;
			
			if($uid === null){
				$uid = $context->getInternalDataValue("auth");
			}
			
			if($task->onlySourceOf){
				
				$item = $dbContext->querySingleEntity("DBIntegral","uid={$uid} and suid={$task->suid} and stype={$task->stype} and sid={$task->sid}");
				
				if(!$item){
					$item = new DBIntegral();
					$item->uid = $uid;
					$item->suid = $task->suid;
					$item->sid = $task->sid;
					$item->stype = $task->stype;
					$item->value = $task->value;
					$item->createTime = time();
					
					$dbContext->insert($item);
					
					$task->results = $item;
				}

			}
			else{
				$item = new DBIntegral();
				$item->uid = $uid;
				$item->suid = $task->suid;
				$item->sid = $task->sid;
				$item->stype = $task->stype;
				$item->value = $task->value;
				$item->createTime = time();
				
				$dbContext->insert($item);
				
				$task->results = $item;
			}

			return false;
		}
		
		if($task instanceof IntegralTotalTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext();
				
			$dbContextTask = new DBContextTask(DB_INTEGRAL);
				
			$context->handle("DBContextTask",$dbContextTask);
				
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}
			
			$uid = $task->uid;
				
			if($uid === null){
				$uid = $context->getInternalDataValue("auth");
			}
			
			$rs = $dbContext->query("SELECT SUM(value) as total FROM ".DBIntegral::tableName()." WHERE uid={$uid}");
			
			$task->results = 0.0;
			
			if($rs){
				if($row = $dbContext->next($rs)){
					$task->results = doubleval($row["total"]);
				}
				$dbContext->free($rs);
			}
			
			return false;
		}
		
		if($task instanceof IntegralFetchTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext();
			
			$dbContextTask = new DBContextTask(DB_INTEGRAL);
			
			$context->handle("DBContextTask",$dbContextTask);
			
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}
				
			$uid = $task->uid;
			
			if($uid === null){
				$uid = $context->getInternalDataValue("auth");
			}
			
			$sql = "uid={$uid} ORDER BY iid DESC";
			
			if($task->pageIndex !== null && $task->pageSize !== null){
				$offset = ($task->pageIndex - 1) * $task->pageSize;
				$sql .= " LIMIT {$offset},{$task->pageSize}";
			}

			$rs = $dbContext->queryEntitys("DBIntegral",$sql);

				
			if($rs){
				while($item = $dbContext->nextObject($rs,"DBIntegral")){
					if(!$task->fetchItem($item)){
						break;
					}
				}
				$dbContext->free($rs);
			}
				
			return false;
		}
		
		
		return true;
	}
	
}

?>