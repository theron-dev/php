<?php

/**
 * apple 支付服务
 * Tasks : ApplePushTask
 * @author zhanghailong
 *
 *				"config" => array(
					"url"=>"https://buy.itunes.apple.com/verifyReceipt"
				),
 */
class ApplePurchaseService extends Service{
	
	
	public function handle($taskType,$task){
		
		if($task instanceof ApplePurchaseTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext();
			
			$dbTask = new DBContextTask();
			$dbTask->key = DB_APPLE;
			
			$context->handle("DBContextTask", $dbTask);
			
			if($dbTask->dbContext){
				$dbContext = $dbTask->dbContext;
			}
			
			$cfg = $this->getConfig();
			
			$uid = $task->uid;
			
			if($uid === null){
				$uid = $context->getInternalDataValue("auth");
			}
			
			$state = DBApplePurchaseStateNone;
			
			$item = $dbContext->querySingleEntity("DBApplePurchase","`product`=".$dbContext->parseValue($task->product)." AND `transaction`=".$dbContext->parseValue($task->transaction));
			
			if($item){
				$state = $item->state;
			}
			else{
				$item = new DBApplePurchase();
				$item->uid = $uid;
				$item->state = DBApplePurchaseStateNone;
				$item->product = $task->product;
				$item->receipt = $task->receipt;
				$item->transaction = $task->transaction;
					
				$dbContext->insert($item);
			}
			
			if($state != DBApplePurchaseStatePurchased){
			
				$url = isset($cfg["url"]) ? $cfg["url"] : "https://buy.itunes.apple.com/verifyReceipt";
				
				$data = array("receipt-data"=>$task->receipt);
				
				$ch = curl_init();
				
				curl_setopt($ch,CURLOPT_URL,$url);
				curl_setopt($ch, CURLOPT_HEADER, 0);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
				
				$resp = curl_exec($ch);
				
				if($resp === false){
					$item->state = DBApplePurchaseStateFailed;
					$item->results = curl_error($ch);
					$item->updateTime = time();
					$dbContext->update($item);
				}
				else{
					$data = json_decode($resp,true);
					$status = isset($data["status"]) ? intval($data["status"]) : 0;
					if($status == 0){
						$item->state = DBApplePurchaseStatePurchased;
						$item->results = $resp;
						$item->updateTime = time();
						$dbContext->update($item);
					}
					else{
						$item->state = DBApplePurchaseStateFailed;
						$item->results = $resp;
						$item->updateTime =time();
						$dbContext->update($item);
					}
				}
				
				curl_close($ch);
			}
			
			$task->state = $state;
			$task->results = $item;
			
			return false;
		}
		
		return true;
	}
}

?>