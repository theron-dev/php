<?php

/**
 * 豆瓣登陆服务
 * @author zhanghailong
 *
 */
class DoubanLoginService extends Service{
	
	public function handle($taskType,$task){
		
		if($task instanceof DoubanLoginTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext(DB_ACCOUNT);
			
			$cfg = $this->getConfig();
		
			if(!$task->appKey && isset($cfg["appKey"])){
				$task->appKey = $cfg["appKey"];
			}
			
			if(!$task->appSecret && isset($cfg["appSecret"])){
				$task->appSecret = $cfg["appSecret"];
			}
			
			
			$userInfo = DoubanLoginService::getDoubanUserInfo($task->appKey, $task->etoken);
			
			if(!isset($userInfo["uid"])){
				throw new AccountException(json_encode($userInfo),ERROR_ACCOUNT);
			}
			
			$douban_uid = $userInfo["uid"];
			
			$user = $dbContext->querySingleEntity("DBAccount","douban_uid='{$douban_uid}' and account='#DOUBAN_{$douban_uid}'");
			
			if($user){
				$auth = $user->uid;
				$user->state = AccountStateNone;
				$user->loginTime = time();

				$dbContext->update($user);
				$_SESSION["auth"] = $auth;
				$context->setInternalDataValue("auth", $auth);
				
				$bind = new AccountBindTask();
				$bind->type = AccountBindTypeDouban;
				$bind->appKey = $task->appKey;
				$bind->appSecret = $task->appSecret;
				$bind->eid = $user->douban_uid;
				$bind->etoken = $task->etoken;
				$bind->expires_in = $task->expires_in;
				
				$context->handle("AccountBindTask",$bind);
				
				if(class_exists("LogTask")){
					$log = new LogTask(LogLevelInfo,"account","douban uid:{$douban_uid} login uid:".$auth);
					$context->handle("LogTask",$log);
				}
			}
			else{
	
				$user = new DBAccount();
				$user->account = "#DOUBAN_".$douban_uid;
				$user->douban_uid = $douban_uid;
				$user->password = DBAccount::generatedPassword();
				$user->title = isset($userInfo["name"]) ? $userInfo["name"]:null;
				$user->state = AccountStateNone;
				$user->loginTime = time();
				$user->updateTime = time();
				$user->createTime = time();
				
				$dbContext->insert($user);
				
				$auth = $user->uid;
				
				$this->insertDoubanUserInfo($dbContext,$auth,$userInfo);
				
				
				$_SESSION["auth"] = $auth;
				$context->setInternalDataValue("auth", $auth);
				
				$bind = new AccountBindTask();
				$bind->type = AccountBindTypeDouban;
				$bind->appKey = $task->appKey;
				$bind->appSecret = $task->appSecret;
				$bind->eid = $user->douban_uid;
				$bind->etoken = $task->etoken;
				$bind->expires_in = $task->expires_in;
				
				$context->handle("AccountBindTask",$bind);

			}

			return false;
		}
		
		if($task instanceof DoubanBindTask){
				
			$context = $this->getContext();
			$dbContext = $context->dbContext(DB_ACCOUNT);
			
			$cfg = $this->getConfig();
			
			if(!$task->appKey && isset($cfg["appKey"])){
				$task->appKey = $cfg["appKey"];
			}
				
			if(!$task->appSecret && isset($cfg["appSecret"])){
				$task->appSecret = $cfg["appSecret"];
			}
			
			$userInfo = DoubanLoginService::getDoubanUserInfo($task->appKey, $task->etoken);
			
			if(!isset($userInfo["uid"])){
				throw new AccountException(json_encode($userInfo),ERROR_ACCOUNT);
			}
				
			$douban_uid = $userInfo["uid"];

			$uid = $task->uid;
			if($uid === null){
				$uid = $context->getInternalDataValue("auth");
				$task->uid = $uid;
			}
			
			$user = $dbContext->get("DBAccount",array("uid"=>$uid));

			if($user){
		
				$bind = new AccountBindTask();
				$bind->uid = $uid;
				$bind->type = AccountBindTypeDouban;
				$bind->appKey = $task->appKey;
				$bind->appSecret = $task->appSecret;
				$bind->eid = $douban_uid;
				$bind->etoken = $task->etoken;
				$bind->expires_in = $task->expires_in;
			
				$context->handle("AccountBindTask",$bind);

			}
			else{
				throw new AccountException("not found account {$uid}", ERROR_USER_NOT_FOUND);
			}
		
			return false;
		}
		
		return true;
	}
	
	public static function getDoubanUserInfo($appkey,$token){
		
		$ch = curl_init();
	
		curl_setopt($ch, CURLOPT_URL, "https://api.douban.com/v2/user/~me?appkey={$appkey}");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Bearer {$token}"));
		
		$rs = curl_exec($ch);
		
		curl_close($ch);
		
		return $rs ? json_decode($rs,true) : null;
	}

	public static function insertDoubanUserInfo($dbContext,$auth,$user){
		
		$info = new DBAccountInfo();
		$info->uid = $auth;
		$info->key = AccountInfoKeyLogo;
		$info->value = $user["avatar"];
		$info->createTime = time();
		$info->updateTime = time();
		
		$dbContext->insert($info);

	}
}

?>