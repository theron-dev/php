<?php
if($library){
	
	define("DB_LOG","log");
	
	require_once "$library/org.hailong.log/db/DBLog.php";
	require_once "$library/org.hailong.log/tasks/LogTask.php";
	require_once "$library/org.hailong.log/tasks/LogClearTask.php";
	require_once "$library/org.hailong.log/services/LogService.php";
}
?>