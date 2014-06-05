<?php

function RESURL($url,$width=null){

	global $library;
	
	$resUrl = require("$library/org.hailong.configs/resource_url.php");
	
	if($width != null){
		$url = str_replace("res:///images", $resUrl."/thumb/$width/images", $url);
	}
	
	return str_replace("res://", $resUrl, $url);
}
