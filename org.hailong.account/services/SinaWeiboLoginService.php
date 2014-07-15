<?php

/**
 * 新浪微薄登陆服务
 * @author zhanghailong
 *
 */
class SinaWeiboLoginService extends Service{
	
	public function handle($taskType,$task){
		
		if($task instanceof SinaWeiboLoginTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext();
			
			$dbTask = new DBContextTask();
			$dbTask->key = DB_ACCOUNT;
			
			$context->handle("DBContextTask", $dbTask);
			
			if($dbTask->dbContext){
				$dbContext = $dbTask->dbContext;
			}
			
			$cfg = $this->getConfig();
		
			if(!$task->appKey && isset($cfg["appKey"])){
				$task->appKey = $cfg["appKey"];
			}
			
			if(!$task->appSecret && isset($cfg["appSecret"])){
				$task->appSecret = $cfg["appSecret"];
			}
			
			if(!$task->appKey || !$task->appSecret){
				global $library;
				$cfg = require "$library/org.hailong.configs/sina_weibo.php";
				$task->appKey = $cfg["appkey"];
				$task->appSecret = $cfg["appsecret"];
			}
			
			$c = new SaeTClientV2( $task->appKey , $task->appSecret , $task->etoken );
			
			$rs = $c->get_uid();
			
			if(!isset($rs["uid"])){
				throw new AccountException(json_encode($rs),ERROR_ACCOUNT);
			}
			
			$weibo_uid = $rs["uid"];
			
			$user = $dbContext->querySingleEntity("DBAccount","weibo_uid='{$weibo_uid}' and account='#SWB_{$weibo_uid}'");
			
			if($user){
				$auth = $user->uid;
				$user->state = AccountStateNone;
				$user->loginTime = time();

				$dbContext->update($user);
				$_SESSION["auth"] = $auth;
				$context->setInternalDataValue("auth", $auth);
				
				$bind = new AccountBindTask();
				$bind->type = AccountBindTypeWeibo;
				$bind->appKey = $task->appKey;
				$bind->appSecret = $task->appSecret;
				$bind->eid = $user->weibo_uid;
				$bind->etoken = $task->etoken;
				$bind->expires_in = $task->expires_in;
				
				$context->handle("AccountBindTask",$bind);
				
				if(class_exists("LogTask")){
					$log = new LogTask(LogLevelInfo,"account","weibo uid:{$weibo_uid} login uid:".$auth);
					$context->handle("LogTask",$log);
				}
			}
			else{
				$rs = $c->show_user_by_id($weibo_uid);
				
				$user = new DBAccount();
				$user->account = "#SWB_".$weibo_uid;
				$user->weibo_uid = $weibo_uid;
				$user->password = DBAccount::generatedPassword();
				$user->title = $rs["name"];
				$user->state = AccountStateNone;
				$user->loginTime = time();
				$user->updateTime = time();
				$user->createTime = time();
				
				$dbContext->insert($user);
				
				$auth = $user->uid;
				
				$this->isertWeiboUserInfo($dbContext,$auth,$rs,$context);
				
				
				$_SESSION["auth"] = $auth;
				$context->setInternalDataValue("auth", $auth);
				
				$bind = new AccountBindTask();
				$bind->type = AccountBindTypeWeibo;
				$bind->appKey = $task->appKey;
				$bind->appSecret = $task->appSecret;
				$bind->eid = $user->weibo_uid;
				$bind->etoken = $task->etoken;
				$bind->expires_in = $task->expires_in;
				
				$context->handle("AccountBindTask",$bind);
				
				$rs = $c->friends_by_id($weibo_uid);
				
				if(isset($rs["users"])){
					foreach($rs["users"] as $item){
						$id = $item["id"];
						if( $dbContext->countForEntity("DBAccount","weibo_uid='{$id}'") ==0){
							$user = new DBAccount();
							$user->account = "#SWB_".$id;
							$user->weibo_uid = $id;
							$user->password = DBAccount::generatedPassword();
							$user->title = $item["name"];
							$user->state = AccountStateGenerated;
							$user->loginTime = time();
							$user->updateTime = time();
							$user->createTime = time();
							$dbContext->insert($user);
								
							$this->isertWeiboUserInfo($dbContext,$user->uid,$item,$context);
							
							$relation = new UserRelationTask();
							$relation->uid = $auth;
							$relation->fuid = $user->uid;
							$relation->source = "sina.weibo";
							$context->handle("UserRelationTask",$relation);
						}
					}
				}
				
				if(class_exists("LogTask")){
					$log = new LogTask(LogLevelInfo,"account","weibo uid:{$weibo_uid} login uid:".$auth);
					$context->handle("LogTask",$log);
				}

			}

			return false;
		}
		
		if($task instanceof SinaWeiboBindTask){
				
			$context = $this->getContext();
			$dbContext = $context->dbContext();
				
			$dbTask = new DBContextTask();
			$dbTask->key = DB_ACCOUNT;
				
			$context->handle("DBContextTask", $dbTask);
				
			if($dbTask->dbContext){
				$dbContext = $dbTask->dbContext;
			}
		
			$cfg = $this->getConfig();
			
			if(!$task->appKey && isset($cfg["appKey"])){
				$task->appKey = $cfg["appKey"];
			}
				
			if(!$task->appSecret && isset($cfg["appSecret"])){
				$task->appSecret = $cfg["appSecret"];
			}
			
			$c = new SaeTClientV2( $task->appKey , $task->appSecret , $task->etoken );
				
			$rs = $c->get_uid();
				
			if(!isset($rs["uid"])){
				throw new AccountException(json_encode($rs),ERROR_ACCOUNT);
			}
				
			$weibo_uid = $rs["uid"];

			$uid = $task->uid;
			if($uid === null){
				$uid = $context->getInternalDataValue("auth");
				$task->uid = $uid;
			}
			
			$user = $dbContext->get("DBAccount",array("uid"=>$uid));

			if($user){
		
				$bind = new AccountBindTask();
				$bind->uid = $uid;
				$bind->type = AccountBindTypeWeibo;
				$bind->appKey = $task->appKey;
				$bind->appSecret = $task->appSecret;
				$bind->eid = $weibo_uid;
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
	

	public static function isertWeiboUserInfo($dbContext,$auth,$user,$context){
		
		$info = new DBAccountInfo();
		$info->uid = $auth;
		$info->key = AccountInfoKeyLogo;
		$info->value = $user["profile_image_url"];
		$info->createTime = time();
		$info->updateTime = time();
		
		$dbContext->insert($info);
		
		$info = new DBAccountInfo();
		$info->uid = $auth;
		$info->key = "location";
		$info->value = $user["location"];
		$info->createTime = time();
		$info->updateTime = time();
		
		$dbContext->insert($info);
		
		$info = new DBAccountInfo();
		$info->uid = $auth;
		$info->key = "description";
		$info->value = $user["description"];
		$info->createTime = time();
		$info->updateTime = time();
		
		$dbContext->insert($info);
		
		$info = new DBAccountInfo();
		$info->uid = $auth;
		$info->key = "url";
		$info->value = "http://weibo.com/".$user["domain"];
		$info->createTime = time();
		$info->updateTime = time();
		
		$dbContext->insert($info);
		
		if(isset($user["name"])){
				
			$index = 1;

			$nick = trim($user["name"]);
			
			$t = new AccountIDCheckNickTask();
			$t->nick = $nick;
			$t->uid = null;
				
			$context->handle("AccountIDCheckNickTask",$t);
				
			while($t->uid !== null){
				
				$t->nick = $nick."_".($index ++);
				$t->uid = null;
					
				$context->handle("AccountIDCheckNickTask",$t);
			}
				
			$info = new DBAccountInfo();
			$info->uid = $auth;
			$info->key = AccountInfoKeyNick;
			$info->value = $t->nick;
			$info->createTime = time();
			$info->updateTime = time();
				
			$dbContext->insert($info);
				
		}
	}
}

?>