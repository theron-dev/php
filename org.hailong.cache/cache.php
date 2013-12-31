<?php

/**
 * 缓存
 */
if($library){
	
	define("DB_CACHE","cache");
	
	require_once "$library/org.hailong.configs/error_code.php";
	
	require_once "$library/org.hailong.cache/CacheException.php";
	require_once "$library/org.hailong.cache/db/DBCache.php";
	require_once "$library/org.hailong.cache/tasks/CacheTask.php";
	require_once "$library/org.hailong.cache/tasks/CacheGetTask.php";
	require_once "$library/org.hailong.cache/tasks/CachePutTask.php";
	require_once "$library/org.hailong.cache/tasks/CacheRemoveTask.php";
	require_once "$library/org.hailong.cache/tasks/CacheCleanupTask.php";
	
	require_once "$library/org.hailong.cache/services/CacheService.php";
	
}
?>