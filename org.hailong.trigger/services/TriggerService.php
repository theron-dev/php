<?php

/**
 *　触发器服务
 * @author zhanghailong
 *
 */
class TriggerService extends Service{
	
	public function handle($taskType,$task){
	
		if($task instanceof TriggerTask){
			
			$cfg = $this->getConfig();

			$ctx = $this->getContext();
			
			if(isset($cfg["triggers"])){
				
				foreach($cfg["triggers"] as $trigger){
					if(isset($trigger["name"]) && isset($trigger["do"]) && isset($trigger["data"])){
						$name = $trigger["name"];
						if(strpos($name,$task->name) === 0){
							$data = $trigger["data"];
							$self = array("inputData"=>$ctx->getInputData(),"internalData"=>$ctx->getInternalData(),"data"=>$task->data);
							$inputData = array();
							
							foreach($data as $key=>$value){
								if(is_string($value) && strpos($value,"@") === 0){
									$inputData[$key] = TriggerService::valueForKeyPath($self, substr($value,1));
								}
								else{
									$inputData[$key] = $value;
								}
							}

							$do = $trigger["do"];
							
							TriggerService::doAction($ctx,$inputData,$do);
							
						}
					}
				}
				
			}
			
			
			return false;
		}
		
		return true;
	}
	
	private static function doAction($ctx,$inputData,$do){
		
		if(is_string($do)){
			$keys = split('\:', $do);
			$taskClass = $keys[0];
			$taskType = $taskClass;
			if(count($keys)>1){
				$taskType = $keys[1];
			}
			if(class_exists($taskClass)){
				$t = new $taskClass();
				TriggerService::fillTask($t,$inputData);
				
				$ctx->handle($taskType,$t);
			}
			else{
				throw new TriggerException("not found class ".$taskClass, ERROR_TRIGGER_NOT_FOUND_CLASS);
			}
		}
		else if(is_array($do)){
			if(isset($do["op"])){
				$op = $do["op"];
				if($op == "*"){
					if(isset($op["do"])){
						foreach($op["do"] as $do){
							TriggerService::doAction($ctx, $inputData, $do);
						}
					}
				}
				else if($op == "+"){
					$exception  = null;
					if(isset($op["do"])){
						foreach($op["do"] as $do){
							try{
								TriggerService::doAction($ctx, $inputData, $do);
							}
							catch(Exception $ex){
								$exception = $ex;
							}
						}
					}
					
					if($exception ){
						throw $exception;
					}
				}
				else{
					throw new TriggerException("not found operator ".$op, ERROR_TRIGGER_NOT_FOUND_OPERATOR);
				}
			}
			else if(isset($do["class"])){
				$taskClass = $do["class"];
				$taskType = $taskClass;
				if(isset($do["type"])){
					$taskType = $do["type"];
				}
				if(class_exists($taskClass)){
					$t = new $taskClass();
					
					if(isset($do["default"])){
						foreach($do["default"] as $key=>$value){
							if(isset($t->$key)){
								$t->key = $value;
							}
						}
					}
					
					TriggerService::fillTask($t,$inputData);
				
					$ctx->handle($taskType,$t);
				}
				else{
					throw new TriggerException("not found class ".$taskClass, ERROR_TRIGGER_NOT_FOUND_CLASS);
				}
			}
		}
	}
	
	private static function valueForKeyPath($object,$keyPath){
		
		$keys = is_array($keyPath) ? $keyPath : split('\.',$keyPath);
		
		if($object && count($keys) >0){
			
			$key = $keys[0];
			$o = null;

			
			if(is_array($object)){
				if(isset($object[$key])){
					$o = $object[$key];
				}
			}
			else if(is_object($object)){
				if(isset($object->$key)){
					$o = $object->$key;
				}
			}
			if(count($keys)>1){
				return TriggerService::valueForKeyPath($o, array_slice($keys, 1));
			}
			else{
				return $o;
			}
		}
		return null;
	}
	
	private static function fillTask($task,$data,$class=null){
		
		if($class == null){
			TriggerService::fillTask($task,$data,get_class($task));
		}
		else{
				
			$classs = class_parents($class);
				
			foreach($classs as $c){
				TriggerService::fillTask($task,$data,$c);
			}
			
			$values = array();
			$t = new $class();
			$prefix = $t->prefix();
			foreach($t as $key=>$value){
				$pkey = $prefix ? $prefix."-".$key : $key;
				
				if(isset($data[$pkey])){
					$task->$key = $data[$pkey];
				}
			}
		}
	}
}

?>