<?php

/**
 * 设备
 */
if($library){
	
	define("DB_LBS","lbs");
	
	require_once "$library/org.hailong.lbs/LBSException.php";
	
	require_once "$library/org.hailong.lbs/db/DBLBSSource.php";
	require_once "$library/org.hailong.lbs/db/DBLBSSearch.php";
	
	require_once "$library/org.hailong.lbs/tasks/LBSTask.php";
	require_once "$library/org.hailong.lbs/tasks/LBSSourceUpdateTask.php";
	require_once "$library/org.hailong.lbs/tasks/LBSSourceRemoveTask.php";
	require_once "$library/org.hailong.lbs/tasks/LBSSearchTask.php";
	require_once "$library/org.hailong.lbs/tasks/LBSDistanceTask.php";
	
	require_once "$library/org.hailong.lbs/services/LBSService.php";
	
}
?>