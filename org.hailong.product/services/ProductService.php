<?php

/**
 *　商品服务
 * @author zhanghailong
 *
 */
class ProductService extends Service{
	
	public function handle($taskType,$task){
	
		if($task instanceof ProductCreateTask){
			
			$config = $this->getConfig();

			$context = $this->getContext();
			$dbContext = $context->dbContext();
			
			$dbContextTask = new DBContextTask(DB_PRODUCT);
			
			$context->handle("DBContextTask",$dbContextTask);
			
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}
			
			$uid = $context->getInternalDataValue("auth");
			
			if($uid){
				
				$item = new DBProduct();
				
				$item->uid = $uid;
				$item->count = $task->count;
				$item->publishCount = $task->count;
				$item->etype = $task->etype;
				$item->eid  = $task->eid;
				$item->price = $task->price;
				$item->salePrice = $task->salePrice;
				$item->target = $task->target;
				
				$dbContext->insert($item);
				$dbContext->commit();
				
				$task->results = $item;
				
			}
			else{
				throw new ProductException("not found uid", ERROR_PRODUCT_NOT_FOUND_UID);
			}
			
			
			return false;
		}
		
		if($task instanceof ProductUpdateTask){
				
			$config = $this->getConfig();
		
			$context = $this->getContext();
			$dbContext = $context->dbContext();
				
			$dbContextTask = new DBContextTask(DB_PRODUCT);
				
			$context->handle("DBContextTask",$dbContextTask);
				
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}
				
			$uid = $context->getInternalDataValue("auth");
				
			if($uid){
		
				$item = $dbContext->get("DBProduct", array("pid"=>intval($task->pid)));
					
				if($item){
					if($item->uid != $uid){
						$t = new AuthorityEntityValidateTask(PRODUCT_ALIAS_ADMIN);
						$t->uid = $uid;
						$context->handle("AuthorityEntityValidateTask",$t);
					}
					
					if($task->count !== null){
						$item->count = $task->count;
						$item->publishCount = $task->count;
					}
					if($task->price !== null){
						$item->price = $task->price;
					}
					if($task->salePrice !== null){
						$item->salePrice = $task->salePrice;
					}
					if($task->target !== null){
						$item->target = $task->target;
					}
					$item->updateTime = time();
					$dbContext->update($item);
					$dbContext->commit();
				}
		
			}
			else{
				throw new ProductException("not found uid", ERROR_PRODUCT_NOT_FOUND_UID);
			}
				
				
			return false;
		}
		
		if($task instanceof ProductRemoveTask){
				
			$config = $this->getConfig();
		
			$context = $this->getContext();
			$dbContext = $context->dbContext();
				
			$dbContextTask = new DBContextTask(DB_PRODUCT);
				
			$context->handle("DBContextTask",$dbContextTask);
				
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}
			
			$uid = $context->getInternalDataValue("auth");
				
			if($uid){
				
				if($task->pid !== null){
					$item = $dbContext->get("DBProduct", array("pid"=>intval($task->pid)));
					
					if($item){
						if($item->uid != $uid){
							$t = new AuthorityEntityValidateTask(PRODUCT_ALIAS_ADMIN);
							$t->uid = $uid;
							$context->handle("AuthorityEntityValidateTask",$t);
						}
						
						$dbContext->delete($item);
						$dbContext->commit();
					}
				}
				else if($task->etype !== null){
					
					$where = "1<>1";
					try{
						$t = new AuthorityEntityValidateTask(PRODUCT_ALIAS_ADMIN);
						$t->uid = $uid;
						$context->handle("AuthorityEntityValidateTask",$t);
						
						$where = "etype={$task->etype}";
						
						if($task->eid !== null){
							$where .= " and eid={$task->eid}";
						}
					}
					catch(Exception $ex){
						
						$where = "uid={$uid} and etype={$task->etype}";
						
						if($task->eid !== null){
							$where .= " and eid={$task->eid}";
						}
					}
					
					$dbContext->delete("DBProduct",$where);
					$dbContext->commit();
				}
		
			}
			else{
				throw new ProductException("not found uid", ERROR_PRODUCT_NOT_FOUND_UID);
			}
				
				
			return false;
		}
		
		if($task instanceof ProductPublishTask){
			
			$config = $this->getConfig();
			
			$context = $this->getContext();
			$dbContext = $context->dbContext();
			
			$dbContextTask = new DBContextTask(DB_PRODUCT);
			
			$context->handle("DBContextTask",$dbContextTask);
			
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}
				
			$uid = $context->getInternalDataValue("auth");
			
			if($uid){
			
				$item = $dbContext->get("DBProduct", array("pid"=>$task->pid));
			
				if($item){
					if($item->uid != $uid){
						$t = new AuthorityEntityValidateTask(PRODUCT_ALIAS_ADMIN);
						$t->uid = $uid;
						$context->handle("AuthorityEntityValidateTask",$t);
					}
						
					if($item->state != DBProductStateSale){
						$item->state = DBProductStateSale;
						$item->updateTime = time();
						
						if($task->endTime !== null){
							$item->endTime = $task->endTime;
						}
						else{
							$item->endTime = null;
						}
						if(intval($task->saleTime) !=0){
							$item->saleTime = $task->saleTime;
						}
						else{
							$item->saleTime = time();
						}
						if($task->count !== null){
							$item->count = $task->count;
							$item->publishCount = $task->count;
						}
						else if(intval($item->count)  === 0){
							$item->count = -1;
							$item->publishCount = -1;
						}
						if($task->price !== null){
							$item->price = $task->price;
						}
						if($task->salePrice !== null){
							$item->salePrice = $task->salePrice;
						}
		
						$dbContext->update($item);
						$dbContext->commit();
					}
				}
				else{
					throw new ProductException("not found product ".$task->pid, ERROR_PRODUCT_NOT_FOUND_PRODUCT);
				}
			
			}
			else{
				throw new ProductException("not found uid", ERROR_PRODUCT_NOT_FOUND_UID);
			}
			
			return false;
		}
		
		if($task instanceof ProductUnpublishTask){
				
			$config = $this->getConfig();
				
			$context = $this->getContext();
			$dbContext = $context->dbContext();
				
			$dbContextTask = new DBContextTask(DB_PRODUCT);
				
			$context->handle("DBContextTask",$dbContextTask);
				
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}
		
			$uid = $context->getInternalDataValue("auth");
				
			if($uid){
					
				$item = $dbContext->get("DBProduct", array("pid"=>$task->pid));
					
				if($item){
					if($item->uid != $uid){
						$t = new AuthorityEntityValidateTask(PRODUCT_ALIAS_ADMIN);
						$t->uid = $uid;
						$context->handle("AuthorityEntityValidateTask",$t);
					}
		
					if($item->state != DBProductStateDisabled){
						$item->state = DBProductStateDisabled;
						$item->updateTime = time();
						$dbContext->update($item);
						$dbContext->commit();
					}
				}
				else{
					throw new ProductException("not found product ".$task->pid, ERROR_PRODUCT_NOT_FOUND_PRODUCT);
				}
					
			}
			else{
				throw new ProductException("not found uid", ERROR_PRODUCT_NOT_FOUND_UID);
			}
				
			return false;
		}
		
		if($task instanceof ProductTradeTask){
			
			
			$config = $this->getConfig();
			
			$context = $this->getContext();
			$dbContext = $context->dbContext();
			
			$dbContextTask = new DBContextTask(DB_PRODUCT);
			
			$context->handle("DBContextTask",$dbContextTask);
			
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}
			
			$count = intval($task->count);
			
			if($count <= 0){
				throw new ProductException("sale count not > 0 ", ERROR_PRODUCT_SALE_COUNT);
			}
			
			$item = $dbContext->querySingleEntity("DBProduct","pid=".intval($task->pid)." for update");

			if($item){

				if($item->state == DBProductStateSale){
					
					if(time() < $item->saleTime){
						throw new ProductException("product not start sale",ERROR_PRODUCT_SALETIME);
					}
					
					if($item->endTime != 0 && time() > $item->endTime){
						throw new ProductException("product sale ended",ERROR_PRODUCT_ENDTIME);
					}
					
					$c = intval($item->count);
					if($c == -1 || $count <= $c){
						$item->count = $c == -1 ? -1 : $c - $count;
						$item->updateTime = time();
						$dbContext->update($item);
						$dbContext->commit();
					}
					else{
						throw new ProductException("product ".$task->pid." count out", ERROR_PRODUCT_COUNT_OUT);
					}
				}
				else{
					throw new ProductException("product ".$task->pid." not sale", ERROR_PRODUCT_NOT_SALE);
				}
			}
			else{
				throw new ProductException("not found product ".$task->pid, ERROR_PRODUCT_NOT_FOUND_PRODUCT);
			}
			
			return false;
			
		}
		
		if($task instanceof ProductUntradeTask){
				
				
			$config = $this->getConfig();
				
			$context = $this->getContext();
			$dbContext = $context->dbContext();
				
			$dbContextTask = new DBContextTask(DB_PRODUCT);
				
			$context->handle("DBContextTask",$dbContextTask);
				
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}
				
			$count = intval($task->count);
				
			if($count <= 0){
				throw new ProductException("sale count not > 0 ", ERROR_PRODUCT_SALE_COUNT);
			}
		
			$item = $dbContext->querySingleEntity("DBProduct","pid=".intval($task->pid)." for update");
		
			if($item){
		
				if($item->state == DBProductStateSale){
					$c = intval($item->count);
					if($c == -1 || $count <= $c){
						$item->count = $c == -1 ? -1 : $c + $count;
						$item->updateTime = time();
						$dbContext->update($item);
						$dbContext->commit();
					}
				}
				else{
					throw new ProductException("product ".$task->pid." not sale", ERROR_PRODUCT_NOT_SALE);
				}
			}
			else{
				throw new ProductException("not found product ".$task->pid, ERROR_PRODUCT_NOT_FOUND_PRODUCT);
			}
				
			return false;
				
		}
		
		if($task instanceof ProductGetForUpdateTask){
			
			$config = $this->getConfig();
			
			$context = $this->getContext();
			$dbContext = $context->dbContext();
			
			$dbContextTask = new DBContextTask(DB_PRODUCT);
			
			$context->handle("DBContextTask",$dbContextTask);
			
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}
			
			$task->results = $dbContext->querySingleEntity("DBProduct","pid=".intval($task->pid)." for update");

			return false;
		}
		
		if($task instanceof ProductGetTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext(DB_PRODUCT);
			
			$results = array();
			
			$sql = "";
			
			if($task->pid !== null){
				$sql = "pid=".intval($task->pid);
			}
			else{
				$etype = intval($task->etype);
				$eid = intval($task->eid);
				$sql = "etype={$etype} AND eid={$eid}";
			}
			
			$sql .= " ORDER BY pid ASC";
			
			$rs = $dbContext->queryEntitys("DBProduct",$sql);
			
			if($rs){
				
				while($item = $dbContext->nextObject($rs,"DBProduct")){

					$results[] = $item;
				}
				
				$dbContext->free($rs);
			}
			
			$task->results = $results;
			
		}
		
		
		return true;
	}
}

?>