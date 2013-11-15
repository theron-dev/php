<?php

/**
 *　Crash服务
 * @author zhanghailong
 *
 */
class CrashService extends Service{
	
	public function handle($taskType,$task){
	
		if($task instanceof CrashTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext(DB_CRASH);
			
			$identifier = trim($task->identifier);
			
			if($identifier){
				$item = new DBCrash();
				
				$item->identifier = $task->identifier;
				$item->version = $task->version;
				$item->build = $task->build;
				$item->systemName = $task->systemName;
				$item->systemVersion = $task->systemVersion;
				$item->model = $task->model;
				$item->deviceName = $task->deviceName;
				$item->deviceIdentifier = $task->deviceIdentifier;
				
				if(is_string($task->exception)){
					$item->exception = $task->exception;
				}
				else if($task->exception){
					$item->exception = json_encode($task->exception);
				}
				
				$item->createTime = time();
				
				$dbContext->insert($item);
			}
			
			return false;
		}
		
		if($task instanceof CrashExportTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext(DB_CRASH);

			$cid = intval($task->cid);
			
			$item = $dbContext->querySingleEntity("DBCrash","cid={$cid}");
			
			if($item){
				
				$cnt = "";
				
				$body = json_decode($item->exception,true);
				
				foreach ($body as $key => $value){
					
					if(is_array($value)){
						
						$cnt .= $key.":\r\n";
						
						foreach ($value as $v){

							$cnt .= $v."\r\n";
						
						}
						
					}
					else{
						$cnt .= $key.":".$value."\r\n";
					}
					
				}
				
				$len = strlen($cnt);
				
				$filename = $item->identifier."_".$item->version."_"
						.$item->build."_".date("Y_m_d_H_i_s",$item->createTime).".crash";
				
				header("Content-Type: text/plain;");
				header("Content-Length: {$len};");
				header("Content-Disposition: attachment; filename=\"{$filename}\"");
				
				echo $cnt;
			}
			else{
				header("HTTP/1.1 404 Not Found");
			}
			
			return false;
		}
	
		return true;
	}
}

?>