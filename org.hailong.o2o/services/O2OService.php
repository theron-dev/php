<?php

/**
 *　O2O 服务
 * @author zhanghailong
 *
 */
class O2OService extends Service{
	
	public function handle($taskType,$task){
	
		if($task instanceof O2OTradeEntityCreateTask){
			
			$context = $this->getContext();
			
			$dbContext = $this->dbContext();
			
			$item = $this->newTradeEntity();
			
			$item->pid = $task->pid;
			$item->removed = 0;
			$item->createTime = time();
			
			if($task->propertys){
				
				foreach($task->propertys as $key=>$value){
					
					$item->$key = $value;
					
				}
				
			}
			
			$dbContext->insert($item);
			
			$task->results = $item;
			
			return false;
		}
		
		if($task instanceof O2OTradeEntityGetTask){
			
			$context = $this->getContext();
				
			$dbContext = $this->dbContext();
			
			$tradeEntityClass = $this->tradeEntityClass();
			
			if($task->eid !== null){
				$task->results = $dbContext->querySingleEntity($tradeEntityClass,"eid=".intval($task->eid));
			}
			else {
				$task->results = $dbContext->querySingleEntity($tradeEntityClass,"pid=".intval($task->pid));
			}

			return false;
		}
		
		if($task instanceof O2OTradeEntityRemoveTask){
				
			$context = $this->getContext();
		
			$dbContext = $this->dbContext();
				
			$tradeEntityClass = $this->tradeEntityClass();

			$sql = "UPDATE `".$tradeEntityClass."` SET `removed`=1 WHERE ";
			
			if($task->eid !== null){
				$sql .= "eid=".intval($task->eid);
			}
			else {
				$sql .= "pid=".intval($task->pid);
			}
			
			$dbContext->query($sql);
		
			return false;
		}
		
		if($task instanceof O2OTradeOrderCreateTask){
			
			$context = $this->getContext();
			
			$dbContext = $this->dbContext();
			
			$item = $this->newTradeOrder();
			
			$item->uid = $context->getInternalDataValue("auth");
			$item->eid = $task->eid;
			$item->status = 0;
			$item->updateTime = $item->createTime = time();
			
			if($task->propertys){
			
				foreach($task->propertys as $key=>$value){
						
					$item->$key = $value;
						
				}
			
			}
		
			$dbContext->insert($item);
			
			$task->results = $item;
			
			return false;
		}
		
		if($task instanceof O2OTradeOrderStatusSetTask){
			
			$context = $this->getContext();
				
			$dbContext = $this->dbContext();
			
			$item = $task->order;
			
			if($item === null){
				$tradeOrderClass = $this->tradeOrderClass();
					
				$item = $dbContext->get($tradeOrderClass,array("oid"=>$task->oid));
			}
			
			if($item){
				
				$item->status = $task->status;
				$item->updateTime = time();
				
				$dbContext->update($item);
				
				$status = new O2ODBTradeOrderStatus();
				
				$status->uid = $context->getInternalDataValue("auth");
				$status->oid = $item->oid;
				$status->status = $task->status;
				$status->remark = $task->remark;
				$status->createTime = $item->updateTime;
				
				$dbContext->insert($status);
				
				$task->order = $item;
			}
			
			return false;
		}

		return true;
	}
	
	
	public function dbContext(){
		
		$context = $this->getContext();
		$cfg = $this->getConfig();
		
		if(isset($cfg["dbKey"])){
			return $context->dbContext($cfg["dbKey"]);
		}
		
		return $context->dbContext();
	}
	
	public function tradeEntityClass(){
		
		$cfg = $this->getConfig();
		$class = "O2ODBTradeEntity";
		
		if(isset($cfg["dbTradeEntity"])){
			$class = $cfg["dbTradeEntity"];
		}
		
		return $class;
	}
	
	public function newTradeEntity(){
		
		$cfg = $this->getConfig();
		$class = "O2ODBTradeEntity";
		
		if(isset($cfg["dbTradeEntity"])){
			$class = $cfg["dbTradeEntity"];
		}
		
		return new $class();
	}
	
	public function tradeOrderClass(){
	
		$cfg = $this->getConfig();
		$class = "O2ODBTradeOrder";
	
		if(isset($cfg["dbTradeOrder"])){
			$class = $cfg["dbTradeOrder"];
		}
	
		return $class;
	}
	
	public function newTradeOrder(){
		
		$cfg = $this->getConfig();
		$class = "O2ODBTradeOrder";
		
		if(isset($cfg["dbTradeOrder"])){
			$class = $cfg["dbTradeOrder"];
		}
		
		return new $class();
	}
}

?>