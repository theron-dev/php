<?php
session_start();

$library = "../..";


$config = require "$library/org.hailong.configs/renren.php";

$code_url = "https://graph.renren.com/oauth/authorize?";

$code_url .= http_build_query(array('response_type'=>'code','client_id'=>$config["appkey"]
	,"redirect_uri"=>$config["redirect_uri"]
	,"display"=>(isset($_REQUEST["display"]) ? $_REQUEST["display"]:null)
	,"scope"=>""));

header("Location: $code_url");

?>