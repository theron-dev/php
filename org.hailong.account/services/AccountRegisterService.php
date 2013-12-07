<?php

/**
 * 帐号注册服务
 * @author zhanghailong
 *
 */
class AccountRegisterService extends Service{
	
	
	
	public function handle($taskType,$task){
		
		$context = $this->getContext();
		$dbContext = $context->dbContext();

		$dbTask = new DBContextTask();
		$dbTask->key = DB_ACCOUNT;
		
		$context->handle("DBContextTask", $dbTask);
		
		if($dbTask->dbContext){
			$dbContext = $dbTask->dbContext;
		}
		
		if($task instanceof AccountEmailValidateTask){
			
			if(strpos($task->email, "@") === false){
				throw new AccountException("email format error", ERROR_USER_EMAIL_FORMAT_ERROR);
			}
			
			$count = $dbContext->countForEntity("DBAccount","email='{$task->email}' for update");

			if($count != 0){
				throw new AccountException("email is exists", ERROR_USER_EMAIL_EXISTS);
			}
		}
		
		if($task instanceof AccountEmailRegisterTask){
			
			$auth = new DBAccount();
			$auth->account = $task->email;
			$auth->email = $task->email;
			$auth->password = DBAccount::encodePassword($task->password);
			$auth->email_verify = DBAccount::generatedVerify();
			$auth->updateTime = time();
			$auth->createTime = time();
			$auth->state = AccountStateNone;
			
			$dbContext->insert($auth);
			
			$task->uid = $auth->uid;
			$task->verify = $auth->email_verify;
			
			if(class_exists("AsyncActiveTask")){
				$async = new AsyncActiveTask();
				$async->taskType = "eMailSendTask";
				$async->taskClass = "eMailSendTask";
				$data = array();
				$data["email-to"] = $task->email;
				$data["email-title"] = USER_REGISTER_EMAIL_TITLE;
				$data["email-body"] = str_replace("{verify}", $auth->email_verify , USER_REGISTER_EMAIL_BODY);
				$async->data = $data;
				
				$context->handle("AsyncActiveTask",$async);
			}
			else if(class_exists("eMailSendTask")){
				$email = new eMailSendTask();
				$email->to = $task->email;
				$email->title = USER_REGISTER_EMAIL_TITLE;
				$email->body = str_replace("{verify}", $auth->email_verify , USER_REGISTER_EMAIL_BODY);

				$context->handle("eMailSendTask", $email);
			}
			
			return false;
		}
		
		if($task instanceof AccountEmailResetVerifyTask){
			
			$auth = $context->getInternalDataValue("auth");
			
			$user = $dbContext->get("DBAccount",array("uid"=>$auth));
			
			if($user){
				$user->email_verify = DBAccount::generatedVerify();
				$user->updateTime = time();
				$dbContext->update($user);
				
				$task->verify = $user->email_verify;
				
				if(class_exists("AsyncActiveTask")){
					$async = new AsyncActiveTask();
					$async->taskType = "eMailSendTask";
					$async->taskClass = "eMailSendTask";
					$data = array();
					$data["email-to"] = $user->email;
					$data["email-title"] = USER_REGISTER_EMAIL_TITLE;
					$data["email-body"] = str_replace("{verify}", $user->email_verify , USER_REGISTER_EMAIL_BODY);
					$async->data = $data;
				
					$context->handle("AsyncActiveTask",$async);
				}
				else if(class_exists("eMailSendTask")){
					$email = new eMailSendTask();
					$email->to = $user->email;
					$email->title = USER_REGISTER_EMAIL_TITLE;
					$email->body = str_replace("{verify}", $user->email_verify , USER_REGISTER_EMAIL_BODY);
				
					$context->handle("eMailSendTask", $email);
				}
			}
			
			return false;
		}
		
		if($task instanceof AccountEmailActiveTask){
			
			$auth = $context->getInternalDataValue("auth");
				
			$user = $dbContext->get("DBAccount",array("uid"=>$auth));
			
			if($user){
				if($user->email_verify != $task->verify){
					throw new AccountException("user verify error", ERROR_USER_VERIFY);
				}
				else{
					$user->email_verify = null;
					$user->updateTime = time();
					$dbContext->update($user);
					
					$bind = new AccountBindTask();
					$bind->eid = $user->email;
					$bind->etoken = $task->verify;
					$bind->type = AccountBindTypeEmail;
					$bind->uid = $auth;
					
					$context->handle("AccountBindTask", $bind);
				}
			}
			else{
				throw new AccountException("not found user", ERROR_USER_NOT_FOUND);
			}
			
			return false;
		}
		
		if($task instanceof AccountResetPasswordTask){
				
			$auth = $context->getInternalDataValue("auth");
				
			$user = $dbContext->querySingleEntity("DBAccount","account='{$task->account}'");
				
			if($user){
				
				$task->password = DBAccount::generatedPassword();
				$user->password = DBAccount::encodePassword($task->password);
				$user->updateTime = time();
				$dbContext->update($user);
			
				if($user->email){
					if(class_exists("AsyncActiveTask")){
						$async = new AsyncActiveTask();
						$async->taskType = "eMailSendTask";
						$async->taskClass = "eMailSendTask";
						$data = array();
						$data["email-to"] = $user->email;
						$data["email-title"] = USER_RESET_PWD_EMAIL_TITLE;
						$data["email-body"] = str_replace("{password}", $task->password , USER_RESET_PWD_EMAIL_BODY);
						$async->data = $data;
			
						$context->handle("AsyncActiveTask",$async);
					}
					else if(class_exists("eMailSendTask")){
						$email = new eMailSendTask();
						$email->to = $user->email;
						$email->title = USER_RESET_PWD_EMAIL_TITLE;
						$email->body = str_replace("{password}", $task->password , USER_RESET_PWD_EMAIL_BODY);
			
						$context->handle("eMailSendTask", $email);
					}
				}
			}
			else{
				throw new AccountException("not found user", ERROR_USER_NOT_FOUND);
			}
				
			return false;
		}
		
		if($task instanceof AccountRegisterTask){
			
			$account = trim($task->account);
			$nick = trim($task->nick);
			
			if(!$account){
				throw new AccountException("not found account", ERROR_USER_NOT_FOUND_ACCOUNT);
			}
			
			$dbContext->lockWrite(array("DBAccount","DBAccountInfo"));
	
			$v = $dbContext->parseValue($task->account);
			
			$user = $dbContext->querySingleEntity("DBAccount","account={$v} OR tel={$v} OR email={$v}");
			
			if(!$user){
				
				if($nick){
					
					$user = $dbContext->querySingleEntity("DBAccountInfo","`key`='".AccountInfoKeyNick."' and `value`=".$dbContext->parseValue($nick));
					if($user){
						$dbContext->unlock();
						throw new AccountException("nick is exists", ERROR_USER_NICK_EXISTS);
					}
				}
				
				$user = new DBAccount();
				$user->account = $account;
				$user->email = $task->email;
				$user->tel = $task->tel;
				$user->password = DBAccount::encodePassword($task->password);
				$user->title = $task->title;
				
				if($user->email !== null){
					$user->email_verify = DBAccount::generatedVerify();
				}
				if($user->tel !== null){
					$user->tel_verify = DBAccount::generatedVerify();
				}
				
				$user->updateTime = time();
				$user->createTime = time();
				$user->state = AccountStateNone;
				
				$dbContext->insert($user);
				
				$task->uid = $user->uid;
				
				if($nick){
						
					$info = new DBAccountInfo();
					$info->uid = $user->uid;
					$info->key = AccountInfoKeyNick;
					$info->value = $nick;
					$info->updateTime = time();
					$info->createTime = time();
					
					$dbContext->insert($info);
		
				}
				
				$dbContext->unlock();
				
			}
			else{
				$dbContext->unlock();
				throw new AccountException("account is exists", ERROR_USER_ACCOUNT_EXISTS);
			}
			
			return false;
		}
		
		if($task instanceof AccountTelVerifyTask){
			
			$tel = trim($task->tel);
			
			$user = $dbContext->querySingleEntity("DBAccount","tel=".$dbContext->parseValue($tel));
			
			if($user){
				$user->tel_verify = DBAccount::generatedVerify();
				$user->updateTime = time();
				$dbContext->update($user);
			}
			else{
				$user = new DBAccount();
				$user->account = $tel;
				$user->tel = $tel;
				$user->tel_verify = DBAccount::generatedVerify();
				$user->state = AccountStateGenerated;
				$user->updateTime = time();
				$user->createTime = time();
				$dbContext->insert($user);
			}
			
			$task->verify = $user->tel_verify;
			
			return false;
		}
		
		if($task instanceof AccountTelRegisterTask){
			
			$tel = trim($task->tel);
			$verify = trim($task->verify);
			$nick = trim($task->nick);
			
			$user = $dbContext->querySingleEntity("DBAccount","tel=".$dbContext->parseValue($tel)." for update");
			
			if($user){
				
				if($user->tel_verify == $verify){
					
					$dbContext->lockWrite(array("DBAccount","DBAccountInfo"));
					
					if($nick){
							
						$u = $dbContext->querySingleEntity("DBAccountInfo","`uid`<>{$user->uid} `key`='".AccountInfoKeyNick."' and `value`=".$dbContext->parseValue($nick));
						if($u){
							$dbContext->unlock();
							throw new AccountException("nick is exists", ERROR_USER_NICK_EXISTS);
						}
					}
					
					$user->password = DBAccount::encodePassword($task->password);
					$user->tel_verify = null;
					
					if($user->state == AccountStateGenerated){
						$user->state = AccountStateNone;
					}
					
					$user->updateTime = time();
					
					$dbContext->update($user);
					
					if($nick){
					
						$info = new DBAccountInfo();
						$info->uid = $user->uid;
						$info->key = AccountInfoKeyNick;
						$info->value = $nick;
						$info->updateTime = time();
						$info->createTime = time();
							
						$dbContext->insert($info);
					}
					
					$dbContext->unlock();
				}
				else{
					throw new AccountException("verify code error",ERROR_USER_VERIFY);
				}
			}
			else{
				throw new AccountException("verify code error",ERROR_USER_VERIFY);
			}
			
			return false;
		}
		
		return true;
	}

}

?>