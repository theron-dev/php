<?php
$library = "..";

require_once "$library/org.hailong.service/service.php";
require_once "$library/org.hailong.ui/ui.php";
require_once "$library/org.hailong.account/account.php";

require_once "configs/config.php";

require_once "crash.php";

session_start();
	
$inputData = array();

foreach($_GET as $key => $value){
	$inputData[$key] = $value;
}
foreach($_POST as $key => $value){
	$inputData[$key] = $value;
}

$data = input();

if(is_array($data)){
	foreach($data as $key => $value){
		$inputData[$key] = $value;
	}
}

$context = new ServiceContext($inputData,config());

$task = new CrashExportTask();

$context->fillTask($task);

$context->handle("CrashExportTask", $task,false);

?>