<?php

/**
 *　交易服务
 * @author zhanghailong
 *
 */
class TradeService extends Service{
	
	public function handle($taskType,$task){
	
		if($task instanceof TradeCreateTask){
			
			$config = $this->getConfig();

			$context = $this->getContext();
			$dbContext = $context->dbContext();
			
			$dbContextTask = new DBContextTask(DB_TRADE);
			
			$context->handle("DBContextTask",$dbContextTask);
			
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}
			
			$uid = $task->uid;
			
			if($uid === null){
				$uid = $context->getInternalDataValue("auth");
			}
			
			if($uid){
				
				$item = new DBTrade();
				$item->puid = $task->puid;
				$item->uid = $uid;
				$item->type = $task->type;
				$item->state = DBTradeStateNone;
				$item->pid = $task->pid;
				$item->unitPrice = $task->unitPrice;
				$item->count = $task->count;
				
				if(is_string($task->body)){
					$item->body = json_encode(array("body"=>$task->body));
				}
				else if($task->body){
					$item->body = json_encode($task->body);
				}
				
				$dbContext->insert($item);
				
				$task->tid = $item->tid;
				
			}
			else{
				throw new TradeException("not found uid", ERROR_TRADE_NOT_FOUND_UID);
			}
			
			
			return false;
		}
		
		if($task instanceof TradeCancelTask){
				
			$config = $this->getConfig();
		
			$context = $this->getContext();
			$dbContext = $context->dbContext();
				
			$dbContextTask = new DBContextTask(DB_TRADE);
				
			$context->handle("DBContextTask",$dbContextTask);
				
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}
				
			$uid = $task->uid;
			
			if($uid === null){
				$uid = $context->getInternalDataValue("auth");
			}
				
			if($uid){
		
				$item = $dbContext->get("DBTrade", array("tid"=>intval($task->tid)));
					
				if($item){
					if($item->uid != $uid){
						throw new TradeException("no permission",ERROR_TRADE_NO_PERMISSION);
					}
					if($item->state != DBTradeStateNone){
						$task->state = $item->state;
						throw new TradeException("Status Unavailable",ERROR_TRADE_STATUS_UNAVIALABLE);
					}
					
					$item->state = DBTradeStateCanceled;
					$item->updateTime = time();
					$dbContext->update($item);
				}
				else{
					throw new TradeException("not found trade",ERROR_TRADE_NOT_FOUND);
				}
			}
			else{
				throw new TradeException("not found uid", ERROR_TRADE_NOT_FOUND_UID);
			}
				
				
			return false;
		}
		
		if($task instanceof TradeConfirmTask){
				
			$config = $this->getConfig();
		
			$context = $this->getContext();
			$dbContext = $context->dbContext();
				
			$dbContextTask = new DBContextTask(DB_TRADE);
				
			$context->handle("DBContextTask",$dbContextTask);
				
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}
				
			$uid = $task->uid;
			
			if($uid === null){
				$uid = $context->getInternalDataValue("auth");
			}
				
			if($uid){
		
				$item = $dbContext->get("DBTrade", array("tid"=>intval($task->tid)));
					
				if($item){
					if($item->uid != $uid){
						throw new TradeException("no permission",ERROR_TRADE_NO_PERMISSION);
					}
					if($item->state != DBTradeStateShipped){
						$task->state = $item->state;
						throw new TradeException("Status Unavailable",ERROR_TRADE_STATUS_UNAVIALABLE);
					}
					
					$item->state = DBTradeStateConfirm;
					$item->updateTime = time();
					$dbContext->update($item);
				}
				else{
					throw new TradeException("not found trade",ERROR_TRADE_NOT_FOUND);
				}
			}
			else{
				throw new TradeException("not found uid", ERROR_TRADE_NOT_FOUND_UID);
			}
				
				
			return false;
		}
		
		if($task instanceof TradePaymentTask){
		
			$config = $this->getConfig();
		
			$context = $this->getContext();
			$dbContext = $context->dbContext();
		
			$dbContextTask = new DBContextTask(DB_TRADE);
		
			$context->handle("DBContextTask",$dbContextTask);
		
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}
		
			$uid = $task->uid;
			
			if($uid === null){
				$uid = $context->getInternalDataValue("auth");
			}
		
			if($uid){
		
				$item = $dbContext->get("DBTrade", array("tid"=>intval($task->tid)));
					
				if($item){
					if($item->uid != $uid){
						throw new TradeException("no permission",ERROR_TRADE_NO_PERMISSION);
					}
					if($item->state != DBTradeStateNone){
						$task->state = $item->state;
						throw new TradeException("Status Unavailable",ERROR_TRADE_STATUS_UNAVIALABLE);
					}
						
					$item->state = DBTradeStatePaid;
					$item->updateTime = time();
					$dbContext->update($item);
				}
				else{
					throw new TradeException("not found trade",ERROR_TRADE_NOT_FOUND);
				}
			}
			else{
				throw new TradeException("not found uid", ERROR_TRADE_NOT_FOUND_UID);
			}
		
		
			return false;
		}
		
		if($task instanceof TradeShippingTask){
		
			$config = $this->getConfig();
		
			$context = $this->getContext();
			$dbContext = $context->dbContext();
		
			$dbContextTask = new DBContextTask(DB_TRADE);
		
			$context->handle("DBContextTask",$dbContextTask);
		
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}
		
			$uid = $task->uid;
			
			if($uid === null){
				$uid = $context->getInternalDataValue("auth");
			}
		
			if($uid){
		
				$item = $dbContext->get("DBTrade", array("tid"=>intval($task->tid)));
					
				if($item){
					if($item->puid !=0 && $item->puid != $uid){
						throw new TradeException("no permission",ERROR_TRADE_NO_PERMISSION);
					}
					if($item->state != DBTradeStatePaid){
						$task->state = $item->state;
						throw new TradeException("Status Unavailable",ERROR_TRADE_STATUS_UNAVIALABLE);
					}
		
					$item->state = DBTradeStateShipped;
					$item->updateTime = time();
					$dbContext->update($item);
				}
				else{
					throw new TradeException("not found trade",ERROR_TRADE_NOT_FOUND);
				}
			}
			else{
				throw new TradeException("not found uid", ERROR_TRADE_NOT_FOUND_UID);
			}
		
		
			return false;
		}
		
		if($task instanceof TradeRefundTask){
		
			$config = $this->getConfig();
		
			$context = $this->getContext();
			$dbContext = $context->dbContext();
		
			$dbContextTask = new DBContextTask(DB_TRADE);
		
			$context->handle("DBContextTask",$dbContextTask);
		
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}
		
			$uid = $task->uid;
				
			if($uid === null){
				$uid = $context->getInternalDataValue("auth");
			}
		
			if($uid){
		
				$item = $dbContext->get("DBTrade", array("tid"=>intval($task->tid)));
					
				if($item){
					if($item->uid != $uid){
						throw new TradeException("no permission",ERROR_TRADE_NO_PERMISSION);
					}
					if($item->state != DBTradeStatePaid || $item->state != DBTradeStateShipped){
						$task->state = $item->state;
						throw new TradeException("Status Unavailable",ERROR_TRADE_STATUS_UNAVIALABLE);
					}
					if($item->refundState != DBTradeRefundStateNone){
						throw new TradeException("Status Unavailable",ERROR_TRADE_STATUS_UNAVIALABLE);
					}
					if($item->unitPrice * $item->count - $item->reduce < $task->price){
						throw new TradeException("refund", ERROR_TRADE_REFUND_PRICE_OUT);
					}
					$item->refundPrice = $task->price;
					$item->refundState = DBTradeRefundStateCommit;
					$item->updateTime = time();
					$dbContext->update($item);
				}
				else{
					throw new TradeException("not found trade",ERROR_TRADE_NOT_FOUND);
				}
			}
			else{
				throw new TradeException("not found uid", ERROR_TRADE_NOT_FOUND_UID);
			}
		
		
			return false;
		}
		
		if($task instanceof TradeRefundCancelTask){
		
			$config = $this->getConfig();
		
			$context = $this->getContext();
			$dbContext = $context->dbContext();
		
			$dbContextTask = new DBContextTask(DB_TRADE);
		
			$context->handle("DBContextTask",$dbContextTask);
		
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}
		
			$uid = $task->uid;
		
			if($uid === null){
				$uid = $context->getInternalDataValue("auth");
			}
		
			if($uid){
		
				$item = $dbContext->get("DBTrade", array("tid"=>intval($task->tid)));
					
				if($item){
					if($item->uid != $uid){
						throw new TradeException("no permission",ERROR_TRADE_NO_PERMISSION);
					}
					if($item->refundState != DBTradeRefundStateCommit){
						$task->state = $item->state;
						throw new TradeException("Status Unavailable",ERROR_TRADE_STATUS_UNAVIALABLE);
					}
					$item->refundPrice = 0.0;
					$item->refundState = DBTradeRefundStateCanceled;
					$item->updateTime = time();
					$dbContext->update($item);
				}
				else{
					throw new TradeException("not found trade",ERROR_TRADE_NOT_FOUND);
				}
			}
			else{
				throw new TradeException("not found uid", ERROR_TRADE_NOT_FOUND_UID);
			}
		
		
			return false;
		}
		
		if($task instanceof TradeRefundCloseTask){
		
			$config = $this->getConfig();
		
			$context = $this->getContext();
			$dbContext = $context->dbContext();
		
			$dbContextTask = new DBContextTask(DB_TRADE);
		
			$context->handle("DBContextTask",$dbContextTask);
		
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}
		
			$uid = $task->uid;
		
			if($uid === null){
				$uid = $context->getInternalDataValue("auth");
			}
		
			if($uid){
		
				$item = $dbContext->get("DBTrade", array("tid"=>intval($task->tid)));
					
				if($item){
					if($item->uid != $uid){
						throw new TradeException("no permission",ERROR_TRADE_NO_PERMISSION);
					}
					if($item->refundState != DBTradeRefundStateCommit){
						$task->state = $item->state;
						throw new TradeException("Status Unavailable",ERROR_TRADE_STATUS_UNAVIALABLE);
					}
					$item->refundState = TradeRefundCloseTask;
					$item->updateTime = time();
					$dbContext->update($item);
				}
				else{
					throw new TradeException("not found trade",ERROR_TRADE_NOT_FOUND);
				}
			}
			else{
				throw new TradeException("not found uid", ERROR_TRADE_NOT_FOUND_UID);
			}
		
		
			return false;
		}
		
		if($task instanceof TradeGetProductCountTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext(DB_TRADE);
			
			$sql = "SELECT SUM(t.count) as count FROM ".DBTrade::tableName()." as t WHERE pid={$task->pid}";
			
			$sql .= " AND state IN (".DBTradeStatePaid.",".DBTradeStateShipped.",".DBTradeStateConfirm.")";
			
			$rs = $dbContext->query($sql);
			
			if($rs){
				if($row  = $dbContext->next($rs)){
					$task->count = intval($row["count"]);
				}
				$dbContext->free($rs);
			}
			
			return false;
		}
		
		return true;
	}
}

?>