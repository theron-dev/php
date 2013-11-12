<?php

/**
 *　金币服务
 * @author zhanghailong
 *
 */
class CoinService extends Service{
	
	public function handle($taskType,$task){
	
		if($task instanceof CoinIncomeTask){
			
			$config = $this->getConfig();

			$context = $this->getContext();
			$dbContext = $context->dbContext();
			
			$dbContextTask = new DBContextTask(DB_COIN);
			
			$context->handle("DBContextTask",$dbContextTask);
			
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}

			$uid = $task->uid;
			
			if($uid === null){
				$uid = $context->getInternalDataValue("auth");
			}
			
			if($uid){
				
				if($task->coin >0.0){
					
					$item = new DBCoinIncome();
					$item->uid=  $uid;
					$item->source = $task->source;
					$item->sid = $task->sid;
					$item->stype = $task->stype;
					$item->coin = $task->coin;
					
					$dbContext->insert($item);
					
					$item = $dbContext->querySingleEntity("DBCoin","uid={$uid} for update");
					
					if($item){
						$item->coin = $item->coin + $task->coin;
						$item->updateTime = time();
						$dbContext->update($item);
					}
					else{
						$item = new DBCoin();
						$item->uid = $uid;
						$item->coin = $task->coin;
						$dbContext->insert($item);
					}
					
				}
				else if($task->coin <0.0){
					
					$item = $dbContext->querySingleEntity("DBCoin","uid={$uid} for update");
					
					if($item && ($item->coin + $task->coin) >=0 ){
						
						$item->coin = $item->coin + $task->coin;
						$item->updateTime = time();
						$dbContext->update($item);
						
						
						$item = new DBCoinIncome();
						$item->uid = $uid;
						$item->source = $task->source;
						$item->sid = $task->sid;
						$item->stype = $task->stype;
						$item->coin = $task->coin;
						
						$dbContext->insert($item);
	
					}
					else{
						throw new CoinException("coin not enough", ERROR_COIN_NOT_ENOUGH);
					}
				}
				
			}
			else{
				throw new CoinException("not found uid", ERROR_COIN_NOT_FOUND_UID);
			}
			
			
			return false;
		}
		
		if($task instanceof CoinGetTask){
				
			$config = $this->getConfig();
		
			$context = $this->getContext();
			$dbContext = $context->dbContext();
				
			$dbContextTask = new DBContextTask(DB_COIN);
				
			$context->handle("DBContextTask",$dbContextTask);
				
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}
				
			$uid = $task->uid;
				
			if($uid === null){
				$uid = $context->getInternalDataValue("auth");
			}
			
			$item = $dbContext->querySingleEntity("DBCoin","uid={$uid}");
				
			if($item){
				$task->coin = $item->coin;
			}
		
			return false;
		}
		
		
		return true;
	}
}

?>