<?php

/**
 * 用户关系服务
 * @author zhanghailong
 *
 */
class UserRelationService extends Service{
	
	public function handle($taskType,$task){
		
		$context = $this->getContext();
		$dbContext = $context->dbContext();

		$dbTask = new DBContextTask();
		$dbTask->key = DB_ACCOUNT;
		
		$context->handle("DBContextTask", $dbTask);
		
		if($dbTask->dbContext){
			$dbContext = $dbTask->dbContext;
		}

		if($task instanceof UserRelationTask){
			
			$relation = $dbContext->querySingleEntity("DBUserRelation","uid={$task->uid} and fuid={$task->fuid}");
			
			if(!$relation){
				$relation = new DBUserRelation();
				$relation->uid = $task->uid;
				$relation->fuid = $task->fuid;
				$relation->source = $task->source;
				$relation->weight = 1;
				$relation->updateTime = time();
				$relation->createTime = time();
				$dbContext->insert($relation);
			}
			else{
				$relation->weight = intval($relation->weight) +1;
				$relation->updateTime = time();
				$dbContext->update($relation);
			}
			
			return false;
		}

		return true;
	}
}

?>