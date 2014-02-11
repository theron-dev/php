<?php

/**
 * 物品推广服务
 * @author zhanghailong
 * @task SpreadRemoveTask GoodsSpreadAskTask
 */
class GoodsSpreadService extends Service{
	
	public function handle($taskType,$task){
		
		
		if($task instanceof SpreadRemoveTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext();
				
			$dbContextTask = new DBContextTask(DB_GOODS);
				
			$context->handle("DBContextTask",$dbContextTask);
				
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}
			
			$rs = $dbContext->queryEntitys("DBGoods","sid={$task->sid}");
			
			if($rs){
				while($goods = $dbContext->nextObject($rs,"DBGoods")){
					
					$t = new GoodsSpreadAskTask();
					$t->etype = $goods->etype;
					$t->eid = $goods->eid;
					
					$context->handle("GoodsSpreadAskTask",$t);
					
					if($t->sid){
						$goods->sid = $t->sid;
						if($t->url){
							$goods->url = $t->url;
						}
						if($t->wapUrl){
							$goods->wapUrl = $t->wapUrl;
						}
						$goods->updateTime = time();
						$dbContext->update($goods);
					}
				}
				$dbContext->free($rs);
			}
			
		}
		
		if($task instanceof GoodsSpreadAskTask){
			
			$context = $this->getContext();
			
			global $library;
				
				
			$cfg =  require "$library/org.hailong.configs/taobao.php";
				
			if($task->etype == DBGoodsExternTypeTaobao || $task->etype == DBGoodsExternTypeTaoke || $task->etype == DBGoodsExternTypeTmall){
				

				$t = new SpreadAskTask();
				$t->type = DBSpreadTypeTaoke;
					
				$context->handle("SpreadAskTask",$t);
					

				if(is_array($t->results) && count($t->results)>0){
						
					$task->sid = $t->results[0]->sid;
					$task->source = "taoke";
					
					$marked = $t->results[0]->marked;
				
					$topClient = new TopClient();
					$topClient->appkey = $cfg["appkey"];
					$topClient->secretKey = $cfg["appsecret"];
					$topClient->format = "json";
					
					$req = new TaobaokeItemsDetailGetRequest();
				
					$req->setFields("num_iid,click_url,price,pic_url,title,item_imgs,desc");

					if(is_int($marked)){
						$req->setPid($marked);
					}
					else{
						$req->setNick($marked);
					}
					
					$req->setNumIids($task->eid);
					if($task->outerCode){
						$req->setOuterCode($task->outerCode);
					}
					
					$resp = $topClient->execute($req);
					
					$errorCode = isset($resp->code) ? $resp->code:0;
					
					if($errorCode == 0 && ($items = isset($resp->taobaoke_item_details->taobaoke_item_detail) ? $resp->taobaoke_item_details->taobaoke_item_detail : null) && count($items) >0){

						$item = $items[0];
						
					
						$t = new URITask();
						$t->url = $item->click_url;
						
						$context->handle("URITask", $t);
						
						if($t->uri){
							$task->url = $t->uri;
						}
						else{
							$task->url = $t->url;
						}
						$task->title = $item->item->title;
						
						$res = upload_from_url($item->item->pic_url,"image");
		
						$task->image = isset($res["image"]) ? $res["image"] : $item->item->pic_url;
						$task->body = $item->item->desc;
						$task->price = $item->item->price;
						$task->unit = DBGoodsUnitNone;
	
						if(count($item->item->item_imgs) >0){
							$task->images = array();
							foreach($item->item->item_imgs as $image){
								$res = upload_from_url($image->url,"image");
								$task->images[] = isset($res["image"]) ? $res["image"] : $image->url;
							}
						}
						
						$req->setFields("num_iid,click_url");
						$req->setIsMobile("true");
							
						$resp = $topClient->execute($req);
							
						$errorCode = isset($resp->code) ? $resp->code:0;
							
						if($errorCode == 0 && ($items = isset($resp->taobaoke_item_details->taobaoke_item_detail) ? $resp->taobaoke_item_details->taobaoke_item_detail : null) && count($items) >0){
							
							$item = $items[0];
	
							$t = new URITask();
							$t->url = $item->click_url;
								
							$context->handle("URITask", $t);
								
							if($t->uri){
								$task->wapUrl = $t->uri;
							}
							else{
								$task->wapUrl = $t->url;
							}
						}
						else{
							throw new GoodsException(json_encode($resp), $errorCode);
						}
					}
					else{
						
						$req = new ItemGetRequest();
						
						$req->setFields("num_iid,title,pic_url,price,detail_url,wap_detail_url");
						$req->setNumIid($task->eid);
						
						$resp = $topClient->execute($req);
							
						$errorCode = isset($resp->code) ? $resp->code:0;
							
						if($errorCode == 0 && isset($resp->item)){
							
							$item = $resp->item;
							
							$task->title = $item->title;
							
							$res = upload_from_url($item->pic_url,"image");
							
							$task->image = isset($res["image"]) ? $res["image"] : $item->pic_url;
	
							$task->body = "";
							$task->price = $item->price;
							$task->unit = DBGoodsUnitNone;
							
							$t = new URITask();
							$t->url = $item->detail_url;
								
							$context->handle("URITask", $t);
								
							if($t->uri){
								$task->url = $t->uri;
							}
							else{
								$task->url = $t->url;
							}
							
							$t = new URITask();
							$t->url = $item->wap_detail_url;
							
							$context->handle("URITask", $t);
							
							if($t->uri){
								$task->wapUrl = $t->uri;
							}
							else{
								$task->wapUrl = $t->url;
							}

						}
						else{
							throw new GoodsException(json_encode($resp), $errorCode);
						}
					}

				}
			}
			
			
			return false;
		}
		
		
		return true;
	}
	
}

?>