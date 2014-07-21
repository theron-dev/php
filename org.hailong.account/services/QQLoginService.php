<?php

/**
 * QQ登陆服务
 * @author zhanghailong
 *
 */
class QQLoginService extends Service{
	
	public function handle($taskType,$task){
		
		if($task instanceof QQLoginTask){
			
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
				$cfg = require "$library/org.hailong.configs/qq.php";
				$task->appKey = $cfg["appkey"];
				$task->appSecret = $cfg["appsecret"];
			}
			
			$openid = $this->get_openid($task->etoken);
			
			$user = $dbContext->querySingleEntity("DBAccount","qq_uid='{$openid}' and account='#QQ_{$openid}'");
			
			if($user){
				$auth = $user->uid;
				
				if($user->state == AccountStateDisabled){
					throw new AccountException("user is disabled", ERROR_USER_IS_DISABLED);
				}
				
				if($user->state == AccountStateGenerated){
					$user->state = AccountStateNone;
					$this->importRelation($context,$dbContext,$task->appKey,$task->etoken,$openid,$auth);
				}
				
				$user->loginTime = time();

				$dbContext->update($user);
				$_SESSION["auth"] = $auth;
				$context->setInternalDataValue("auth", $auth);
				
				$bind = new AccountBindTask();
				$bind->type = AccountBindTypeQQ;
				$bind->appKey = $task->appKey;
				$bind->appSecret = $task->appSecret;
				$bind->eid = $user->qq_uid;
				$bind->etoken = $task->etoken;
				$bind->expires_in = $task->expires_in;
				
				$context->handle("AccountBindTask",$bind);
				
				if(class_exists("LogTask")){
					$log = new LogTask(LogLevelInfo,"account","qq uid:{$openid} login uid:".$auth);
					$context->handle("LogTask",$log);
				}
			}
			else{

				$rs = $this->get("user/get_user_info", $task->appKey, $task->etoken, $openid);
				
				$user = new DBAccount();
				$user->account = "#QQ_".$openid;
				$user->qq_uid = $openid;
				$user->password = DBAccount::generatedPassword();
				$user->title = isset($rs["nickname"]) ? $rs["nickname"] : "#QQ_".$openid;
				$user->state = AccountStateNone;
				$user->loginTime = time();
				$user->updateTime = time();
				$user->createTime = time();
				
				$dbContext->insert($user);
				
				$auth = $user->uid;
				
				$this->isertQQInfo($dbContext,$auth,$rs,$context);
				
				
				$this->importRelation($context,$dbContext,$task->appKey,$task->etoken,$openid,$auth);
				
				
				$_SESSION["auth"] = $auth;
				$context->setInternalDataValue("auth", $auth);
				
				$bind = new AccountBindTask();
				$bind->type = AccountBindTypeQQ;
				$bind->appKey = $task->appKey;
				$bind->appSecret = $task->appSecret;
				$bind->eid = $user->qq_uid;
				$bind->etoken = $task->etoken;
				$bind->expires_in = $task->expires_in;
				
				$context->handle("AccountBindTask",$bind);
				
				if(class_exists("LogTask")){
					$log = new LogTask(LogLevelInfo,"account","qq uid:{$openid} login uid:".$auth);
					$context->handle("LogTask",$log);
				}

			}

			return false;
		}
		
		if($task instanceof QQBindTask){
				
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
			
			$openid = $this->get_openid($task->etoken);
				
			$uid = $task->uid;
			if($uid === null){
				$uid = $context->getInternalDataValue("auth");
				$task->uid = $uid;
			}
				
			$user = $dbContext->get("DBAccount",array("uid"=>$uid));
			
			if($user){
			
				$bind = new AccountBindTask();
				$bind->uid = $uid;
				$bind->type = AccountBindTypeQQ;
				$bind->appKey = $task->appKey;
				$bind->appSecret = $task->appSecret;
				$bind->eid = $openid;
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
	
	public static function isertQQInfo($dbContext,$auth,$user,$context){
	
		$logo = false;
		
		if(isset($user["figureurl_2"])){
			$logo = $user["figureurl_2"];
		}
		else if(isset($user["figureurl_1"])){
			$logo = $user["figureurl_1"];
		}
		else if(isset($user["figureurl"])){
			$logo = $user["figureurl"];
		}
		else if(isset($user["head"])){
			$logo = $user["head"];
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
		
		if(isset($user["gender"])){
			$sex = $user["gender"];
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
		
		if(isset($user["level"])){
			$info = new DBAccountInfo();
			$info->uid = $auth;
			$info->key = "level";
			$info->value = $user["level"];
			$info->createTime = time();
			$info->updateTime = time();
		
			$dbContext->insert($info);
		}
		

		if(isset($user["vip"])){
			
			$info = new DBAccountInfo();
			$info->uid = $auth;
			$info->key = "vip";
			$info->value = $user["vip"];
			$info->createTime = time();
			$info->updateTime = time();
		
			$dbContext->insert($info);
		}
		
		$location = false;
		
		if(isset($user["location"])){
			$location = $user["location"];
		}
		
		if($location){
			$info = new DBAccountInfo();
			$info->uid = $auth;
			$info->key = "location";
			$info->value = $location;
			$info->createTime = time();
			$info->updateTime = time();
		
			$dbContext->insert($info);
		}
		
		if(isset($user["nickname"])){
			
			$index = 1;
			
			$nick = trim($user["nickname"]);
			
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
	
	public static function importRelation($context,$dbContext,$appkey,$token,$openid,$auth){
		
		$rs = QQLoginService::get("relation/get_idollist", $appkey, $token, $openid);
		
		if(isset($rs["data"]["info"])){
			foreach($rs["data"]["info"] as $item){
				$id = $item["openid"];
				if( $dbContext->countForEntity("DBAccount","qq_uid='{$id}'") ==0){
					$user = new DBAccount();
					$user->account = "#QQ_".$id;
					$user->qq_uid = $id;
					$user->password = DBAccount::generatedPassword();
					$user->title = isset($item["nickname"]) ? $item["nickname"] : $item["nick"];
					$user->state = AccountStateGenerated;
					$user->loginTime = time();
					$user->updateTime = time();
					$user->createTime = time();
					$dbContext->insert($user);
		
					QQLoginService::isertQQInfo($dbContext,$user->uid,$item,$context);
		
					$relation = new UserRelationTask();
					$relation->uid = $auth;
					$relation->fuid = $user->uid;
					$relation->source = "qq";
					$context->handle("UserRelationTask",$relation);
				}
			}
		}
		
		$rs = QQLoginService::get("relation/get_fanslist", $appkey, $token, $openid);
		
		if(isset($rs["data"]["info"])){
			foreach($rs["data"]["info"] as $item){
				$id = $item["openid"];
				if( $dbContext->countForEntity("DBAccount","qq_uid='{$id}'") ==0){
					$user = new DBAccount();
					$user->account = "#QQ_".$id;
					$user->qq_uid = $id;
					$user->password = DBAccount::generatedPassword();
					$user->title = isset($item["nickname"]) ? $item["nickname"] : $item["nick"];
					$user->state = AccountStateGenerated;
					$user->loginTime = time();
					$user->updateTime = time();
					$user->createTime = time();
					$dbContext->insert($user);
		
					QQLoginService::isertQQInfo($dbContext,$user->uid,$item,$context);
		
					$relation = new UserRelationTask();
					$relation->uid = $user->uid;
					$relation->fuid = $auth;
					$relation->source = "qq";
					$context->handle("UserRelationTask",$relation);
				}
			}
		}
	}

	public static function get_openid($token)
	{
	    $graph_url = "https://graph.qq.com/oauth2.0/me?access_token=".$token;
	
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	    curl_setopt($ch, CURLOPT_URL, $graph_url);
	    $result =  curl_exec($ch);
	    curl_close($ch);
	    
	    if (strpos($result, "callback") !== false)
	    {
	        $lpos = strpos($result, "(");
	        $rpos = strrpos($result, ")");
	        $result  = substr($result, $lpos + 1, $rpos - $lpos -1);
	    }
	
	    $user = json_decode($result,true);
	    if (isset($user["error"]))
	    {
	    	throw new AccountException($user["error_description"], $user["error"]);
	    }
	    
	    return $user["openid"];
	}
	
	public static function get($alias,$appKey,$token,$openid){
		
		$graph_url = "https://graph.qq.com/{$alias}?oauth_consumer_key={$appKey}&access_token={$token}&openid={$openid}";
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_URL, $graph_url);
		$result =  curl_exec($ch);
		curl_close($ch);
			
		if (strpos($result, "callback") !== false)
		{
			$lpos = strpos($result, "(");
			$rpos = strrpos($result, ")");
			$result  = substr($result, $lpos + 1, $rpos - $lpos -1);
		}
		
		$rs = json_decode($result,true);
		
		if(!$rs){
			var_dump($graph_url);
			echo "<br />";
			var_dump($result);
			echo "<br />";
		}
		
		if (isset($rs["error"]))
		{
			var_dump($graph_url);
			echo "<br />";
			var_dump($result);
			echo "<br />";
			
			throw new AccountException($rs["error_description"], $rs["error"]);
		}
			
		return $rs;
	}
	
	public static function get_userinfo($token,$openid){
		return $this->get("get_user_info", $token, $openid);
	}
	
	public static function get_idollist($token,$openid){
		return $this->get("get_idollist", $token, $openid);
	}
}

?>