<?php

/**
 * Renren登陆服务
 * @author zhanghailong
 *
 */
class RenrenLoginService extends Service{
	
	public function handle($taskType,$task){
		
		if($task instanceof RenrenLoginTask){
			
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
			
			$sc = new RenrenRestApiService($task->appKey,$task->appSecret);
			
			$renren_uid = false;
			
			if(!$task->user || !isset($task->user["id"])){
				$res = $sc->rr_post_curl('users.getLoggedInUser',  array('access_token'=>$task->etoken));
				if($res && isset($res["uid"])){
					$res = $sc->rr_post_curl('users.getInfo',  array('access_token'=>$task->etoken,'uids'=>$res["uid"]));
					if($res && count($res) >0){
						$task->user = $res[0];
						$renren_uid = $task->user["uid"];
					}
					else{
						throw new AccountException("renren access token error", ERROR_ACCOUNT);
					}
				}
				else{
					throw new AccountException("renren access token error", ERROR_ACCOUNT);
				}
			}
			else{
				$renren_uid = $task->user["id"];
			}
			
			$user = $dbContext->querySingleEntity("DBAccount","renren_uid='{$renren_uid}' and account='#RR_{$renren_uid}'");
			
			if($user){
				
				$auth = $user->uid;
				
				if($user->state == AccountStateDisabled){
					throw new AccountException("user is disabled", ERROR_USER_IS_DISABLED);
				}
				
				if($user->state == AccountStateGenerated){
					$user->state = AccountStateNone;
					$this->importRelation($sc,$context,$dbContext,$task->etoken,$auth);
				}
				
				$user->loginTime = time();
				
				$dbContext->update($user);
				$_SESSION["auth"] = $auth;
				$context->setInternalDataValue("auth", $auth);
				
				$bind = new AccountBindTask();
				$bind->type = AccountBindTypeRenren;
				$bind->appKey = $task->appKey;
				$bind->appSecret = $task->appSecret;
				$bind->eid = $user->renren_uid;
				$bind->etoken = $task->etoken;
				$bind->expires_in = $task->expires_in;
				
				$context->handle("AccountBindTask",$bind);
				
				if(class_exists("LogTask")){
					$log = new LogTask(LogLevelInfo,"account","renren uid:{$renren_uid} login uid:".$auth);
					$context->handle("LogTask",$log);
				}
			}
			else{
				
				$user = new DBAccount();
				$user->account = "#RR_".$renren_uid;
				$user->renren_uid = $openid;
				$user->password = DBAccount::generatedPassword();
				$user->title = isset($task->user["name"]) ? $task->user["name"] : "#RR_".$renren_uid;
				$user->state = AccountStateNone;
				$user->loginTime = time();
				$user->updateTime = time();
				$user->createTime = time();
				
				$dbContext->insert($user);
				
				$auth = $user->uid;
				
				$this->isertRenrenInfo($dbContext,$auth,$task->user);
				
				
				$this->importRelation($sc,$context,$dbContext,$task->etoken,$auth);
				
				
				$_SESSION["auth"] = $auth;
				$context->setInternalDataValue("auth", $auth);
				
				$bind = new AccountBindTask();
				$bind->type = AccountBindTypeRenren;
				$bind->appKey = $task->appKey;
				$bind->appSecret = $task->appSecret;
				$bind->eid = $user->renren_uid;
				$bind->etoken = $task->etoken;
				$bind->expires_in = $task->expires_in;
				
				$context->handle("AccountBindTask",$bind);
				
				if(class_exists("LogTask")){
					$log = new LogTask(LogLevelInfo,"account","renren uid:{$renren_uid} login uid:".$auth);
					$context->handle("LogTask",$log);
				}
			}
			
			$res = $sc->rr_post_curl('friends.getFriends',  array('access_token'=>$task->etoken));
				
			echo json_encode($task->user)."<br />";
			
			echo json_encode($res)."<br />";

			return false;
		}
		
		return true;
	}
	
	public static function isertRenrenInfo($dbContext,$auth,$user){
	
		$logo = false;
	
		if(isset($user["headurl"])){
			$logo = $user["headurl"];
		}
		else if(isset($user["mainurl"])){
			$logo = $user["mainurl"];
		}
		else if(isset($user["tinyurl"])){
			$logo = $user["tinyurl"];
		}
		else if(isset($user["avatar"]) && count($user["avatar"]) >0){
			$logo = $user["avatar"][0]["url"];
		}
	
		if($logo){
			$info = new DBAccountInfo();
			$info->uid = $auth;
			$info->key = AccountInfoKeyLogo;
			$info->value = $logo;
			$info->createTime = time();
			$info->updateTime = time();
	
			$dbContext->insert($info);
		}
	
		$sex = false;
	
		if(isset($user["sex"])){
			$sex = $user["sex"];
		}
	
		if($sex){
			$info = new DBAccountInfo();
			$info->uid = $auth;
			$info->key = AccountInfoKeySex;
			$info->value = $sex;
			$info->createTime = time();
			$info->updateTime = time();
	
			$dbContext->insert($info);
		}
	
	}
	
	public static function importRelation($sc,$context,$dbContext,$token,$auth){
	
		$rs = $sc->rr_post_curl('friends.getFriends',  array('access_token'=>$token));
		
		if(!$rs || ( isset($rs["error_code"]) && intval($rs["error_code"]) ) ){
			return ;
		}
		
		foreach ($rs as $item){
			$id = isset($item["id"]) ? $item["id"] : $item["uid"];
			if( $dbContext->countForEntity("DBAccount","renren_uid='{$id}'") ==0){
				$user = new DBAccount();
				$user->account = "#RR_".$id;
				$user->renren_uid = $id;
				$user->password = DBAccount::generatedPassword();
				$user->title = isset($item["name"]) ? $item["name"] : "#RR_".$id;
				$user->state = AccountStateGenerated;
				$user->loginTime = time();
				$user->updateTime = time();
				$user->createTime = time();
				$dbContext->insert($user);
			
				RenrenLoginService::isertRenrenInfo($dbContext,$user->uid,$item);
			
				$relation = new UserRelationTask();
				$relation->uid = $auth;
				$relation->fuid = $user->uid;
				$relation->source = "renren";
				$context->handle("UserRelationTask",$relation);
			}
		}
		
	}
	
}

?>