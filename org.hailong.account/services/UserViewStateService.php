<?php

/**
 * 用户界面状态服务
 * @author zhanghailong
 *
 */
class UserViewStateService extends Service{
	
	public function handle($taskType,$task){
		
		$context = $this->getContext();
		$dbContext = $context->dbContext();

		$dbTask = new DBContextTask();
		$dbTask->key = DB_ACCOUNT;
		
		$context->handle("DBContextTask", $dbTask);
		
		if($dbTask->dbContext){
			$dbContext = $dbTask->dbContext;
		}
		
		if($task instanceof UserViewStateSaveTask){
			$auth = $context->getInternalDataValue("auth");
			if(!$auth){
				$auth = 0;
			}
			
			$viewState = $dbContext->querySingleEntity("DBUserViewState","uid=$auth and session='{$task->session}' and alias='{$task->alias}'");
			
			if($viewState){
				$viewState->saveTime = time();
				if(isset($_SERVER['REMOTE_ADDR'])){
					$viewState->saveSource =$_SERVER['REMOTE_ADDR'];
				}
				$viewState->updateTime = time();
				$viewState->data = $task->data;
				$dbContext->update($viewState);
			}
			else{
				$viewState = new DBUserViewState();
 				$viewState->uid = $auth;
				$viewState->alias = $task->alias;
				$viewState->data = $task->data;
				$viewState->session = $task->session;
				$viewState->saveTime = time();
				if(isset($_SERVER['REMOTE_ADDR'])){
					$viewState->saveSource =$_SERVER['REMOTE_ADDR'];
				}
				$viewState->updateTime = time();
				$viewState->createTime = time();

				$dbContext->insert($viewState);
			}

			return false;
		}
		
		if($task instanceof UserViewStateLoadTask){
			
			$auth = $context->getInternalDataValue("auth");
			if(!$auth){
				$auth = 0;
			}
		
			$viewState = $dbContext->querySingleEntity("DBUserViewState","uid={$auth} and session='{$task->session}' and alias='{$task->alias}'");
			if($viewState){
				$task->data = $viewState->data;
			}

			return false;
		}
		
		if($task instanceof UserViewStateClearTask){
			
			$auth = $context->getInternalDataValue("auth");
			if(!$auth){
				$auth = 0;
			}
			
			$where = "uid={$auth}";
			
			if($task->session !==false){
				$where .= " and session='{$task->session}'";
			}
			
			$dbContext->delete("DBUserViewState",$where);
			
			return false;
		}
		
		return true;
	}
}

?>