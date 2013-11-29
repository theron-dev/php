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
		
		if($task instanceof PrizeCreateTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext(DB_PRIZE);
			
			$uid = $context->getInternalDataValue("auth");
				
			$item = new DBPrize();
			$item->uid = $uid;
			$item->title = $task->title;
			$item->body = $task->body;
			$item->rule = $task->rule;
			$item->period = $task->period;
			$item->createTime = $item->updateTime = time();
			
			$dbContext->insert($item);
			
			if($task->images){
				
				foreach ($task->images as $image){
					
					$img = new DBPrizeImage();
					$img->uid = $uid;
					$img->pid = $item->pid;
					$img->uri = isset($image["uri"]) ? $image["uri"] : null;
					$img->width = isset($image["width"]) ? $image["width"] : 0;
					$img->height = isset($image["height"]) ? $image["height"] : 0;
					$img->title =  isset($image["title"]) ? $image["title"] : null;
					$img->updateTime = $img->createTime = time();
					
					$dbContext->insert($img);
					
				}
			}
			
			return false;
		}
		
		if($task instanceof PrizeRemoveTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext(DB_PRIZE);
				
			$uid = $context->getInternalDataValue("auth");
				
			$pid = intval($task->pid);
			
			$item = $dbContext->get("DBPrize",array("pid"=>$pid));
			
			if($item){
				
				if($item->uid != $uid){
					
					$t = new AuthorityEntityValidateTask(PrizeAdminAuthorityEntity);
					
					$context->handle("AuthorityEntityValidateTask",$t);
					
				}
				
				$dbContext->delete($item);
				$dbContext->query("DELETE FROM ".DBPrizeImage::tableName()." WHERE pid=".$pid);
				
				$t = new ProductRemoveTask();
				$t->etype = PrizeProductEntityType;
				$t->eid = $item->pid;
				
				$context->handle("ProductRemoveTask",$t);
			}
			
			return false;
		}
		
		
		
		return true;
	}
	
}

?>