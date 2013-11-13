<?php

/**
 * 单向关注
 */
if($library){
	
	define("DB_CONCERN","concern");
	
	define("CACHE_CONCERN_TIMESTAMP","concern/t");
	define("CACHE_CONCERN_USER_TIMESTAMP","concern/{uid}/t");
	
	require_once "$library/org.hailong.configs/error_code.php";
	
	require_once "$library/org.hailong.concern/db/DBConcern.php";
	require_once "$library/org.hailong.concern/services/ConcernService.php";
	
	require_once "$library/org.hailong.concern/tasks/ConcernTask.php";
	require_once "$library/org.hailong.concern/tasks/ConcernAuthTask.php";
	require_once "$library/org.hailong.concern/tasks/ConcernCreateTask.php";
	require_once "$library/org.hailong.concern/tasks/ConcernCancelTask.php";
	require_once "$library/org.hailong.concern/tasks/ConcernBlockTask.php";
	require_once "$library/org.hailong.concern/tasks/ConcernBlockCancelTask.php";
	require_once "$library/org.hailong.concern/tasks/ConcernFetchUserTask.php";
	require_once "$library/org.hailong.concern/tasks/ConcernCheckTask.php";
	require_once "$library/org.hailong.concern/tasks/ConcernFansCountTask.php";
	require_once "$library/org.hailong.concern/tasks/ConcernFollowCountTask.php";
	require_once "$library/org.hailong.concern/tasks/ConcernIsFollowTask.php";
	require_once "$library/org.hailong.concern/tasks/ConcernFetchFansTask.php";
	require_once "$library/org.hailong.concern/tasks/ConcernIsFansTask.php";
	require_once "$library/org.hailong.concern/tasks/ConcernIsMutualTask.php";
	require_once "$library/org.hailong.concern/tasks/ConcernMutualCountTask.php";
}
?>