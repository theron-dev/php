<?php
/**
 * QDD
 */
if($library){

	define("DB_TOP","top");

	require_once "$library/org.hailong.configs/error_code.php";
	require_once "$library/org.hailong.db/db.php";
	require_once "$library/org.hailong.service/service.php";
	
	require_once "$library/org.hailong.top/TopException.php";
	
	require_once "$library/org.hailong.top/db/DBTop.php";
	require_once "$library/org.hailong.top/db/DBTopItem.php";
	
	require_once "$library/org.hailong.top/tasks/TopTask.php";
	require_once "$library/org.hailong.top/tasks/TopItemTask.php";
	require_once "$library/org.hailong.top/tasks/TopRemoveTask.php";
	require_once "$library/org.hailong.top/tasks/TopSearchTask.php";
	
	require_once "$library/org.hailong.top/services/TopService.php";
	
}
?>