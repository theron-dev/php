<?php

if(isset($_REQUEST["code"])){

	session_start();
	
	$library = "../..";
	
	require_once "$library/org.hailong.renren/renren.php";
	
	$config = require "$library/org.hailong.configs/renren.php";
	
	require_once "$library/org.hailong.account.web/configs/config.php";
	
	$token_url = "https://graph.renren.com/oauth/token?";
	
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
		
		$token = json_decode($response,true);
		
		$context = new ServiceContext(array(),config());
		
		$task = new RenrenLoginTask();
		$task->appKey = $config["appkey"];
		$task->appSecret = $config["appsecret"];
		$task->etoken = $token["access_token"];
		$task->expires_in = $token["expires_in"];
		$task->user = $token["user"];
		
		try {
			$context->handle("RenrenLoginTask",$task);
	
			setcookie("RENREN_TOKEN",$token["access_token"]);
			setcookie("RENREN_EXPIRES",$token["expires_in"]);
			
			$ret = "/";
			
			if(isset($_COOKIE["LOGIN-RETURN"])){
				$ret = $_COOKIE["LOGIN-RETURN"];
				setcookie("LOGIN-RETURN","",time() - 1000);
			}
			
			header("Location: ".$ret);
		}
		catch(Exception $ex){
			$ret = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "/";
			header("Location: ".$ret);
		}

	}
	else {
		$ret = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "/";
		header("Location: ".$ret);
	}
}
else{
	$ret = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "/";
	header("Location: ".$ret);
}

?>