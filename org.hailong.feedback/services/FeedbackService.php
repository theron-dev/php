<?php

/**
 *　反馈服务
 * @author zhanghailong
 *
 */
class FeedbackService extends Service{
	
	public function handle($taskType,$task){
	
		if($task instanceof FeedbackTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext(DB_FEEDBACK);
			
			$identifier = trim($task->identifier);
			
			if($identifier){
				
				$item = new DBFeedback();
				
				$item->uid = $context->getInternalDataValue("auth");
				$item->did = $context->getInternalDataValue("device-did");
				$item->identifier = $task->identifier;
				$item->version = $task->version;
				$item->build = $task->build;
				$item->systemName = $task->systemName;
				$item->systemVersion = $task->systemVersion;
				$item->model = $task->model;
				$item->deviceName = $task->deviceName;
				$item->deviceIdentifier = $task->deviceIdentifier;
				$item->body = $task->body;
				
				$item->createTime = time();
				
				$dbContext->insert($item);
			}
			
			return false;
		}
	
		return true;
	}
}

?>