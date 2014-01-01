<?php

/**
 * 终端设备验证服务　记录终端设备信息　生成did　以便做数据差异同步
 * @author zhanghailong
 * 共享内部参数: 
 *  device-did 设备ID
 *  device-unique 设备标示
 */
class DeviceAuthService extends Service{
	
	public function handle($taskType,$task){
		
		if($taskType == "DeviceAuthTask"){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext();
			
			$config = $this->getConfig();
			
			$anonymous = isset($config["anonymous"]) ? $config["anonymous"] : false;
			
			if($task->did){
				$device = $dbContext->get("DBDevice",array("did"=>$task->did));
				if($device == null){
					if(!$anonymous){
						throw new DeviceException("not found device",ERROR_NOT_FOUND_DEVICE_ID);
					}
					return false;
				}
				
				$context->setInternalDataValue("device-did",$device->did);
				$context->setInternalDataValue("device-unique",$device->unique);
				$context->setInternalDataValue("device-type",$device->type);
			}
			else{

				$unique = $task->unique;
				$type = $task->type;

				$device = $dbContext->querySingleEntity("DBDevice","`unique`='$unique' AND `type` =$type ");

				if($device == null){
					
					if($task->unique){
						$device = new DBDevice();
						$device->name = $task->name;
						$device->unique = $task->unique;
						$device->type = $task->type;
						$device->systemName = $task->systemName;
						$device->systemVersion = $task->systemVersion;
						$device->model = $task->model;
						$device->updateTime = time();
						$device->createTime = time();
						$dbContext->insert($device);
					}
					else{
						if(!$anonymous){
							throw new DeviceException("not found device",ERROR_NOT_FOUND_DEVICE_ID);
						}
						return false;
					}
				}
				else{
					$device->name = $task->name;
					$device->systemName = $task->systemName;
					$device->systemVersion = $task->systemVersion;
					$device->model = $task->model;
					$device->updateTime = time();
					$dbContext->update($device);
				}

				$context->setOutputDataValue("device-did",$device->did);
				$context->setInternalDataValue("device-did",$device->did);
				$context->setInternalDataValue("device-unique",$device->unique);
				$context->setInternalDataValue("device-type",$type);
			}
			
			return false;
		}
		
		return true;
	}
}

?>