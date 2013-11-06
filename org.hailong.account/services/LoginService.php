<?php

/**
 * 登陆服务
 * @author zhanghailong
 *
 */
class LoginService extends Service{
	
	public function handle($taskType,$task){
		
		$context = $this->getContext();
		$dbContext = $context->dbContext();

		$dbTask = new DBContextTask();
		$dbTask->key = DB_ACCOUNT;
		
		$context->handle("DBContextTask", $dbTask);
		
		if($dbTask->dbContext){
			$dbContext = $dbTask->dbContext;
		}

		if($task instanceof LoginTask){
		
			$password = $task->md5 && $task->md5 != "false" ? $task->password : DBAccount::encodePassword($task->password);
			$account = $task->account;
			
			if(strpos($account, "#") === 0){
				$account = substr($account, 1);
			}
			
			$user = $dbContext->querySingleEntity("DBAccount","(`tel`='{$task->account}' or `account`='{$task->account}' or `email`='{$task->account}') and `password`='{$password}'");
			if($user){
				$auth = $user->uid;
				$user->loginTime = time();
				$dbContext->update($user);
				$_SESSION["auth"] = $auth;
				$context->setInternalDataValue("auth", $auth);
				
				if(class_exists("LogTask")){
					$log = new LogTask(LogLevelInfo,"account","login uid:".$auth);
					$context->handle("LogTask",$log);
				}
			}
			else{
				throw new AccountException("user or password error",ERROR_USER_AUTH_LOGINNAME_OR_PASSWORD);
			}

			return false;
		}
		
		if($task instanceof LogoutTask){
			
			$auth = isset($_SESSION["auth"]) ? $_SESSION["auth"] : null;
			$_SESSION["auth"] = null;
			$context->setInternalDataValue("auth", null);
			
			if($auth && class_exists("LogTask")){
				$log = new LogTask(LogLevelInfo,"account","logout uid:".$auth);
				$context->handle("LogTask",$log);
			}

			return false;
		}
		
		return true;
	}
}

?>