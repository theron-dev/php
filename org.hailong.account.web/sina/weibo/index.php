<?php
session_start();

$library = "../../..";

require_once "$library/org.hailong.sina.weibo/weibo.php";

$config = require "$library/org.hailong.configs/sina_weibo.php";

$o = new SaeTOAuthV2( $config["appkey"] , $config["appsecret"] );

$state =  array();

if(isset($_REQUEST["ret"])){
	$state["ret"] = $_REQUEST["ret"];
}

if(isset($_REQUEST["appid"])){
	$state["appid"] = $_REQUEST["appid"];
}

if(isset($_REQUEST["did"])){
	$state["did"] = $_REQUEST["did"];
}

if(isset($_REQUEST["token"])){
	$state["token"] = $_REQUEST["token"];
}

if(isset($_REQUEST["uid"])){
	$state["uid"] = $_REQUEST["uid"];
}



$code_url = $o->getAuthorizeURL( $config["redirect_uri"] , "code",json_encode($state),isset($_REQUEST["display"]) ? $_REQUEST["display"] : NULL);

header("Location: $code_url");

?>