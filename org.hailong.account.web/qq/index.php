<?php
session_start();

$library = "../..";


$config = require "$library/org.hailong.configs/qq.php";

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


$code_url = "https://graph.qq.com/oauth2.0/authorize?";

$code_url .= http_build_query(array('response_type'=>'code','client_id'=>$config["appkey"]
	,"redirect_uri"=>$config["redirect_uri"]
	,"display"=>(isset($_REQUEST["display"]) ? $_REQUEST["display"]:null)
	,"scope"=>"add_topic,get_idollist,add_share"
	,"state"=>json_encode($state)));

header("Location: $code_url");

?>