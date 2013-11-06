<?php
session_start();

$library = "../../..";

require_once "$library/org.hailong.sina.weibo/weibo.php";

$config = require "$library/org.hailong.configs/sina_weibo.php";

require_once "$library/org.hailong.account.web/configs/config.php";

$o = new SaeTOAuthV2( $config["appkey"] , $config["appsecret"] );

if (isset($_REQUEST['code'])) {
	$keys = array();
	$keys['code'] = $_REQUEST['code'];
	$keys['redirect_uri'] = $config["redirect_uri"] ;
	try {
		
		$token = $o->getAccessToken( 'code', $keys ) ;
		
		setcookie("SWB_TOKEN",$token["access_token"]);
		setcookie("SWB_EXPIRES",$token["expires_in"]);
			
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
			
			$task = new SinaWeiboBindTask();
			$task->appKey = $config["appkey"];
			$task->appSecret = $config["appsecret"];
			$task->etoken = $token["access_token"];
			$task->expires_in = $token["expires_in"];

			$context->handle("SinaWeiboBindTask",$task);
		}
		else{
			
			$task = new SinaWeiboLoginTask();
			$task->appKey = $config["appkey"];
			$task->appSecret = $config["appsecret"];
			$task->etoken = $token["access_token"];
			$task->expires_in = $token["expires_in"];
			
			$context->handle("SinaWeiboLoginTask",$task);

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

	} catch (Exception $e) {
		$ret = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "/error.php";
		$ret .= "?errorCode={$e->getCode()}&error=".urlencode($e->getMessage());
		header("Location: ".$ret);
	}
}
else{
	$ret = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "/error.php";
	$ret .= "?errorCode=0&error=".urlencode("not found code");
	header("Location: ".$ret);
}

?>