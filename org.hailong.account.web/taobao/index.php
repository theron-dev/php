<?php
session_start();

$library = "../..";


$config = require "$library/org.hailong.configs/taobao.php";

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


$code_url = "https://oauth.taobao.com/authorize?";

$code_url .= http_build_query(array('response_type'=>'code','client_id'=>$config["appkey"]
	,"redirect_uri"=>$config["redirect_uri"]
	,"view"=>(isset($_REQUEST["view"]) ? $_REQUEST["view"]:null)
	,"scope"=>"item"
	,"state"=> base64_encode(json_encode($state)) ));

header("Location: $code_url");

?>