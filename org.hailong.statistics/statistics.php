<?php

/**
 * 店铺
 */
if($library){
	
	define("DB_STATISTICS","statistics");
	define("CACHE_PATH_STATISTICS","statistics");
	
	require_once "$library/org.hailong.configs/error_code.php";
	require_once "$library/org.hailong.cache/cache.php";
	
	require_once "$library/org.hailong.statistics/StatisticsException.php";
	require_once "$library/org.hailong.statistics/db/DBStatistics.php";
	require_once "$library/org.hailong.statistics/db/DBStatisticsUniversal.php";
	require_once "$library/org.hailong.statistics/tasks/StatisticsTask.php";
	require_once "$library/org.hailong.statistics/services/StatisticsService.php";
}
?>