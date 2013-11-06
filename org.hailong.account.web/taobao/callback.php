<?php

if(isset($_REQUEST["code"])){

	session_start();
	
	$library = "../..";

	$config = require "$library/org.hailong.configs/taobao.php";
	
	require_once "$library/org.hailong.account.web/configs/config.php";
	
	$token_url = "https://oauth.taobao.com/token?";
	
	$params = http_build_query(array("grant_type"=>"authorization_code","client_id"=>$config["appkey"]
		,"client_secret"=>$config["appsecret"]
		,"redirect_uri"=>$config["redirect_uri"]
		,"code"=>$_REQUEST["code"]
		));
	
	$curl = curl_init();
	
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);

	curl_setopt($curl, CURLOPT_URL, $token_url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1 );
	curl_setopt($curl, CURLOPT_POST,TRUE);
	curl_setopt($curl, CURLOPT_POSTFIELDS,$params);
	
	$response = curl_exec($curl);
	
	$status = curl_getinfo($curl);
	
	curl_close($curl);
	
	if(intval($status["http_code"])==200){
		
		$token = json_decode($response,true);
		
		if($token && !isset($token["error"])){
		
			$context = new ServiceContext(array(),config());
			
			$task = new AuthTask();
		
			try{
				$context->handle("AuthTask", $task);
			}
			catch(Exception $ex){

			}
			
			$auth = $context->getInternalDataValue("auth");
			
			if(!$auth){
				$task = new TaobaoLoginTask();
				$task->appKey = $config["appkey"];
				$task->appSecret = $config["appsecret"];
				$task->etoken = $token["access_token"];
				$task->expires_in = $token["expires_in"];
				$task->taobao_uid = $token["taobao_user_id"];
				$task->nick = $token["taobao_user_nick"];
				
				try {
					$context->handle("TaobaoLoginTask",$task);
			
					setcookie("TAOBAO_TOKEN",$token["access_token"]);
					setcookie("TAOBAO_EXPIRES",$token["expires_in"]);
					
					$ret = "/";
		
					if(isset($_REQUEST["state"])){
						$rstate = json_decode(base64_decode( urldecode( $_REQUEST["state"])));
						if(isset($rstate->ret)){
							$ret = $rstate->ret;
						}
						if(isset($rstate->appid) && isset($rstate->did)){
							$task = new AppAutoAuthTask();
							$task->did = $rstate->did;
							$task->appid = $rstate->appid;
							
							$context->handle("AppAutoAuthTask",$task);
						}
			
					}
				
					if(isset($_COOKIE["LOGIN-RETURN"])){
						$ret = $_COOKIE["LOGIN-RETURN"];
						setcookie("LOGIN-RETURN","",time() - 1000);
					}
					
					header("Location: ".$ret);
					
					exit;
				}
				catch(Exception $ex){
					echo $ex->getMessage();
				}
			}
			else{
				$task = new AccountBindTask();
				$task->type = AccountBindTypeTaobao;
				$task->appKey = $config["appkey"];
				$task->appSecret = $config["appsecret"];
				$task->etoken = $token["access_token"];
				$task->expires_in = $token["expires_in"];
				$task->eid = $token["taobao_user_id"];

				try {
					$context->handle("AccountBindTask",$task);
						
					setcookie("TAOBAO_TOKEN",$token["access_token"]);
					setcookie("TAOBAO_EXPIRES",$token["expires_in"]);
						
					$ret = "/";
		
					if(isset($_REQUEST["state"])){
						$rstate = json_decode(base64_decode( urldecode( $_REQUEST["state"])));
						if(isset($rstate->ret)){
							$ret = $rstate->ret;
						}
						if(isset($rstate->appid) && isset($rstate->did)){
							$task = new AppAutoAuthTask();
							$task->did = $rstate->did;
							$task->appid = $rstate->appid;
							
							$context->handle("AppAutoAuthTask",$task);
						}
			
					}
				
					if(isset($_COOKIE["LOGIN-RETURN"])){
						$ret = $_COOKIE["LOGIN-RETURN"];
						setcookie("LOGIN-RETURN","",time() - 1000);
					}
					
					header("Location: ".$ret);
					
					exit;
				}
				catch(Exception $ex){
					echo $ex->getMessage();
				}
			}
		}
		else{
			echo $response;
		}
	}
	else {
		$ret = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "/";
		$ret .= "?errorCode={$status["http_code"]}&error=".urlencode($status["http_status"]);
		header("Location: ".$ret);
	}
}
else{
	$ret = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "/";
		$ret .= "?errorCode=0&error=".urlencode("not found code");
		header("Location: ".$ret);
}

?>