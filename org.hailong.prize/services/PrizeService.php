<?php

/**
 * 奖品服务
 * @author zhanghailong
 * @task PrizeBuyTask
 */
class PrizeService extends Service{
	
	public function handle($taskType,$task){
		
		if($task instanceof PrizeBuyTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext(DB_PRIZE);
			
			$uid = $context->getInternalDataValue("auth");
			
			$t = new ProductGetForUpdateTask();
			
			$t->pid = $task->productId;
			
			$context->handle("ProductGetForUpdateTask",$t);
			
			$product = $t->results;
			
			$t = new TradeCreateTask();
			
			$t->pid = $task->productId;
			$t->puid = $product->uid;
			$t->body = $task->body;
			$t->count = 1;
			$t->type = DBTradeTypeCoin;
			$t->unitPrice = $product->salePrice;
			
			$context->handle("TradeCreateTask",$t);
			
			$tid = $t->tid;
			
			$t = new ProductTradeTask();
			
			$t->pid = $task->productId;
			$t->count = 1;
			
			try{
				$context->handle("ProductTradeTask",$t);
			}
			catch(Exception $ex){
				
				$t = new TradeCancelTask();
				$t->tid = $tid;
				
				$context->handle("TradeCancelTask",$t);
				
				throw $ex;
			}
			
			$t = new CoinIncomeTask();
			$t->coin = - $product->salePrice;
			$t->source = "prize";
			$t->sid = $task->productId;
			$t->stype = PrizeProductEntityType;
			
			try{
				$context->handle("CoinIncomeTask",$t);
			}
			catch(Exception $ex){
			
				$t = new TradeCancelTask();
				$t->tid = $tid;
			
				$context->handle("TradeCancelTask",$t);
			
				$t = new ProductUntradeTask();
				
				$t->pid = $task->productId;
				$t->count = 1;
				
				$context->handle("ProductUntradeTask",$t);
				
				throw $ex;
			}
			
			$t = new TradePaymentTask();
			
			$t->tid = $tid;
			
			$context->handle("TradePaymentTask",$t);
			
			return false;
		}
		
		return true;
	}
	
}

?>