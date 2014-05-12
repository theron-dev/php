<?php

/**
 * 计数器
 */
if($library){
	
	define("DB_COUNTER","counter");
	
	require_once "$library/org.hailong.configs/error_code.php";
	
	require_once "$library/org.hailong.counter/db/DBCounter.php";
	
	require_once "$library/org.hailong.counter/tasks/CounterTask.php";
	require_once "$library/org.hailong.counter/tasks/CounterAuthTask.php";
	require_once "$library/org.hailong.counter/tasks/CounterAddTask.php";
	require_once "$library/org.hailong.counter/tasks/CounterPullTask.php";
	
	require_once "$library/org.hailong.counter/services/CounterService.php";
}
?>