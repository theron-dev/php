<?php

set_time_limit(0);

header("Content-Type: text/html;charset=UTF-8");
header("Access-Control-Allow-Origin: *");

$timestamp = doubleval( time()) + microtime();

$library ="..";

global $CFG_DEBUG;
global $CFG_RUNTIME;

require_once "$library/org.hailong.configs/cfg.php";
require_once "$library/org.hailong.service/service.php";

$inputData = inputData();

if($_FILES){

	$files = upload();
	if($files && is_array($files)){
		foreach($files as $name=>$value){
			$inputData[$name] = $value;
		}
	}
}

$context = new ServiceContext($inputData);

$config = isset($inputData["config"]) ? $inputData["config"] : null;

$rs = array();

if($config){
	require_once "$library/org.hailong.configs/{$config}.php";
	
	$context->setDbContext(getDefaultDBContext());
	
	if(function_exists($config)){
		$rs = $context->run($config(),true);
	}
}

$rs["timestamp"] = time();

if($CFG_DEBUG){
	$rs["run-time"] = ( doubleval( time()) + microtime() ) - $timestamp;
}

output($rs,$inputData);

?>