<?php

/**
 * 验证服务
 * @author zhanghailong
 *
 */
class AuthService extends Service{
	
	public function handle($taskType,$task){
		
		if($task instanceof AuthTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext();
			
			$dbTask = new DBContextTask();
			$dbTask->key = DB_ACCOUNT;
			
			$context->handle("DBContextTask", $dbTask);
			
			if($dbTask->dbContext){
				$dbContext = $dbTask->dbContext;
			}
			
			if($context->getInternalDataValue("auth") === null){
				$auth = isset($_SESSION["auth"]) ? $_SESSION["auth"] : null;
				if($auth){
					$context->setInternalDataValue("auth", $auth);
				}
				else{
					throw new AccountException("login timeout", ERROR_USER_LOGIN_TIMEOUT);
				}
			}
		}
		
		return true;
	}
}

?>