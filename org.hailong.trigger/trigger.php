<?php

/**
 * 触发器
 */

if($library){
	
	define("DB_TRIGGER","trigger");
	
	require_once "$library/org.hailong.configs/error_code.php";
	
	require_once "$library/org.hailong.trigger/tasks/TriggerTask.php";
	
	require_once "$library/org.hailong.trigger/services/TriggerService.php";
}
?>