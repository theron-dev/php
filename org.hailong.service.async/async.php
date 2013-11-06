<?php

/**
 * 异步任务
 */
if($library){

	global $async_lock;
	global $sync_php;
	
	$async_lock = "$library/org.hailong.service.async/tmp/.async";
	$sync_php = "$library/org.hailong.service.async/sync.php";
	
	if(substr($sync_php,0,1) != '/'){
		$sync_php = dirname(__FILE__)."/".$sync_php;
	}
	
	define("DB_ASYNC","async");
	
	require_once "$library/org.hailong.service/service.php";
	
	require_once "$library/org.hailong.service.async/db/DBAsyncTask.php";
	require_once "$library/org.hailong.service.async/tasks/AsyncActiveTask.php";
	require_once "$library/org.hailong.service.async/tasks/AsyncResetTask.php";
	require_once "$library/org.hailong.service.async/tasks/AsyncCleanupTask.php";
	require_once "$library/org.hailong.service.async/services/AsyncTaskService.php";
	
	
}
?>