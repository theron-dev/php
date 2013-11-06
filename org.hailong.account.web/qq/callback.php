<?php

if(isset($_REQUEST["code"])){

	session_start();
	
	$library = "../..";
	
	$config = require "$library/org.hailong.configs/qq.php";
	
	require_once "$library/org.hailong.account.web/configs/config.php";
	
	$token_url = "https://graph.qq.com/oauth2.0/token?";
	
	$token_url .= http_build_query(array("grant_type"=>"authorization_code","client_id"=>$config["appkey"]
		,"client_secret"=>$config["appsecret"]
		,"redirect_uri"=>$config["redirect_uri"]
		,"code"=>$_REQUEST["code"]
		));
	
	$curl = curl_init();
	
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);

	curl_setopt($curl, CURLOPT_URL, $token_url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1 );
	
	$response = curl_exec($curl);
	
	$status = curl_getinfo($curl);
	
	curl_close($curl);
	
	if(intval($status["http_code"])==200){
		
		$token = array();
		
		parse_str($response, $token);
		
		setcookie("QQ_TOKEN",$token["access_token"]);
		setcookie("QQ_EXPIRES",$token["expires_in"]);
		
		$context = new ServiceContext(array(),config());
		
		$ret = "/";
		
		if(isset($_REQUEST["state"])){
			$rstate = json_decode(urldecode( $_REQUEST["state"]));
		}
		else{
			$rstate = array();
		}
		
		if(isset($rstate->ret)){
			$ret = $rstate->ret;
		}
		
		if(isset($rstate->appid) && isset($rstate->token) && isset($rstate->did) && isset($rstate->uid)){
				
			$task = new AppAuthTask();
			$task->uid = $rstate->uid;
			$task->did = $rstate->did;
			$task->appid = $rstate->appid;
			$task->token = $rstate->token;
			$context->handle("AppAuthTask",$task);
				
			$task = new QQBindTask();
			$task->appKey = $config["appkey"];
			$task->appSecret = $config["appsecret"];
			$task->etoken = $token["access_token"];
			$task->expires_in = $token["expires_in"];
		
			$context->handle("QQBindTask",$task);
		}
		else{
				
			$task = new QQLoginTask();
			$task->appKey = $config["appkey"];
			$task->appSecret = $config["appsecret"];
			$task->etoken = $token["access_token"];
			$task->expires_in = $token["expires_in"];
				
			$context->handle("QQLoginTask",$task);
		
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
	}
	else {
		$ret = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "/error.php";
		$ret .= "?errorCode={$e->getCode()}&error=".urlencode($e->getMessage());
		header("Location: ".$ret);
	}
}
else{
	$ret = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "/error.php";
	$ret .= "?errorCode={$e->getCode()}&error=".urlencode($e->getMessage());
	header("Location: ".$ret);
}

?>