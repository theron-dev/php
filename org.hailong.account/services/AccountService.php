<?php

/**
 * 帐户服务
 * Tasks : AccountIDTask AccountPasswordChangeTask AccountByIDTask AccountIDCheckTelTask AccountIDCheckEmailTask AccountIDCheckNickTask
 * @author zhanghailong
 *
 */
class AccountService extends Service{
	
	private $accountByIDCache;
	private $accountInfoByIDCache;
	
	public function __construct(){
		$this->accountByIDCache = array();
		$this->accountInfoByIDCache = array();
	}
	
	public function handle($taskType,$task){
		
		if($task instanceof AccountIDTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext();
			
			$dbTask = new DBContextTask();
			$dbTask->key = DB_ACCOUNT;
			
			$context->handle("DBContextTask", $dbTask);
			
			if($dbTask->dbContext){
				$dbContext = $dbTask->dbContext;
			}
			
			$user = $dbContext->querySingleEntity("DBAccount","account='{$task->account}'");
			
			if($user){
				$task->uid = $user->uid;
				$context->setOutputDataValue("account-uid", $user->uid);
			}

			return false;
		}
		
		if($task instanceof AccountByIDTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext();
			
			$dbTask = new DBContextTask();
			$dbTask->key = DB_ACCOUNT;
			
			$context->handle("DBContextTask", $dbTask);
			
			if($dbTask->dbContext){
				$dbContext = $dbTask->dbContext;
			}
			
			$user = null;
			$userInfo = null;
			if(isset($this->accountByIDCache[$task->uid])){
				$user = $this->accountByIDCache[$task->uid];
			}
			if($user == null){
				$user = $dbContext->querySingleEntity("DBAccount","uid={$task->uid}");
				$this->accountByIDCache[$task->uid] = $user;
			}
			if(isset($this->accountInfoByIDCache[$task->uid])){
				$userInfo = $this->accountInfoByIDCache[$task->uid];
			}
			if($userInfo === null){
				$userInfo = $dbContext->querySingleEntity("DBAccountInfo","`uid`={$task->uid} and `key`='".AccountInfoKeyLogo."'");
				if($userInfo==null){
					$this->accountInfoByIDCache[$task->uid] = false;
				}
				else{
					$this->accountInfoByIDCache[$task->uid] = $userInfo;
				}
			}
			
			if($user){
				$task->account = $user->account;
				$task->title = $user->title;
				$task->tel = $user->tel;
				$task->email = $user->email;
			}
			if($userInfo){
				$task->logo = $userInfo->value;
			}
			return false;
		}
		
		if($task instanceof AccountIDCheckTelTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext();
			
			$dbTask = new DBContextTask();
			$dbTask->key = DB_ACCOUNT;
			
			$context->handle("DBContextTask", $dbTask);
			
			if($dbTask->dbContext){
				$dbContext = $dbTask->dbContext;
			}
			
			$user = $dbContext->querySingleEntity("DBAccount","tel='{$task->tel}' and isnull(tel_verify)");
			if($user){
				$task->uid = $user->uid;
				$context->setOutputDataValue("account-uid", $user->uid);
			}
			else{
				$user = $dbContext->querySingleEntity("DBAccount","tel='{$task->tel}'");
				if(!$user && $task->tel){
					$password = DBAccount::generatedPassword();
					$user = new DBAccount();
					$user->account = $task->tel;
					$user->tel = $task->tel;
					$user->password = DBAccount::encodePassword($password);
					$user->tel_verify = DBAccount::generatedVerify();
					$user->state = AccountStateGenerated;
					$user->updateTime = time();
					$user->createTime = time();
					$dbContext->insert($user);
					
					if(class_exists("SMSSendTask")){
						$body  = str_replace("{tel}",$task->tel, str_replace("{password}", $password , USER_AUTO_REGISTER_SMS));
						if(class_exists("AsyncActiveTask")){
							$async = new AsyncActiveTask();
							$async->taskType = "SMSSendTask";
							$async->taskClass = "SMSSendTask";
							$data = array();
							$data["sms-tel"] = $task->tel;
							$data["sms-body"] = $body;
							$async->data = $data;
							$context->handle("AsyncActiveTask",$async);
						}
						else{
							$sms = new SMSSendTask();
							$sms->tel = $task->tel;
							$sms->body = $body;
							$context->handle("SMSSendTask",$sms);
						}
						
					}
				}
			}
			return false;
		}
		
		if($task instanceof AccountIDCheckEmailTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext();
			
			$dbTask = new DBContextTask();
			$dbTask->key = DB_ACCOUNT;
			
			$context->handle("DBContextTask", $dbTask);
			
			if($dbTask->dbContext){
				$dbContext = $dbTask->dbContext;
			}
			
			$user = $dbContext->querySingleEntity("DBAccount","email='{$task->email}' and isnull(email_verify)");
			if($user){
				$task->uid = $user->uid;
				$context->setOutputDataValue("account-uid", $user->uid);
			}
			else{
				$user = $dbContext->querySingleEntity("DBAccount","email='{$task->email}'");
				if(!$user && $task->tel){
					$password = DBAccount::generatedPassword();
					$user = new DBAccount();
					$user->account = $task->email;
					$user->email = $task->email;
					$user->password = DBAccount::encodePassword($password);
					$user->email_verify = DBAccount::generatedVerify();
					$user->state = AccountStateGenerated;
					$user->updateTime = time();
					$user->createTime = time();
					$dbContext->insert($user);
					
					if(class_exists("eMailSendTask")){
						$body  = str_replace("{email}",$task->email, str_replace("{password}", $password , USER_AUTO_REGISTER_EMAIL));
						if(class_exists("AsyncActiveTask")){
							$async = new AsyncActiveTask();
							$async->taskType = "eMailSendTask";
							$async->taskClass = "eMailSendTask";
							$data = array();
							$data["email-to"] = $task->email;
							$data["email-title"] = USER_REGISTER_EMAIL_TITLE;
							$data["email-body"] = $body;
							$async->data = $data;
							$context->handle("AsyncActiveTask",$async);
						}
						else{
							$email = new eMailSendTask();
							$email->email = $task->email;
							$email->title = USER_REGISTER_EMAIL_TITLE;
							$email->body = $body;
							$context->handle("eMailSendTask",$email);
						}
						
					}
				}
			}
			return false;
		}
		
		if($task instanceof AccountPasswordChangeTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext();
			
			$dbTask = new DBContextTask();
			$dbTask->key = DB_ACCOUNT;
			
			$context->handle("DBContextTask", $dbTask);
			
			if($dbTask->dbContext){
				$dbContext = $dbTask->dbContext;
			}
			
			$auth = $context->getInternalDataValue("auth");
			
			$password = $task->md5 && $task->md5 != "false" ? $task->password : DBAccount::encodePassword($task->password);
			
			$user = $dbContext->querySingleEntity("DBAccount","uid={$auth} and password='{$password}'");
			
			if($user){
				$user->password = DBAccount::encodePassword($task->newpassword);
				$dbContext->update($user);
				if(class_exists("LogTask")){
					$log = new LogTask(LogLevelInfo,"account","user change password uid:".$auth);
					$context->handle("LogTask",$log);
				}
			}
			else{
				throw new AccountException("user or password error",ERROR_USER_AUTH_LOGINNAME_OR_PASSWORD);
			}
			
			return false;
		}
		
		if($task instanceof AccountIDCheckNickTask){
				
			$context = $this->getContext();
			$dbContext = $context->dbContext();
				
			$dbTask = new DBContextTask();
			$dbTask->key = DB_ACCOUNT;
				
			$context->handle("DBContextTask", $dbTask);
				
			if($dbTask->dbContext){
				$dbContext = $dbTask->dbContext;
			}
				
			$user = $dbContext->querySingleEntity("DBAccountInfo","`key`='".AccountInfoKeyNick."' and `value`='{$task->nick}'");
			if($user){
				$task->uid = $user->uid;
			}

			return false;
		}
		
		if($task instanceof AccountIDByBindTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext(DB_ACCOUNT);

			$etype = intval($task->etype);
			$eid = intval($task->eid);
			
			$user = $dbContext->querySingleEntity("DBAccountBind","type={$etype} AND eid={$eid}");
			
			if($user){
				$task->uid = $user->uid;
			}
			
			return false;
		}
		
		return true;
	}
}

?>