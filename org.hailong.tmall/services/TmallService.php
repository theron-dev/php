<?php

/**
 * tmall服务
 * @author zhanghailong
 *
 */
class TmallService extends Service{
	
	public function handle($taskType,$task){
		
		if($task instanceof TmallSelectedTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext();
			
			$dbTask = new DBContextTask();
			$dbTask->key = DB_TMALL;
			
			$context->handle("DBContextTask", $dbTask);
			

			
		}
		
		return true;
	}
	
}

?>