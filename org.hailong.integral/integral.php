<?php

/**
 * 积分
 */
if($library){
	
	define("DB_INTEGRAL","integral");
	
	
	require_once "$library/org.hailong.configs/error_code.php";

	
	require_once "$library/org.hailong.integral/IntegralException.php";
	
	require_once "$library/org.hailong.integral/db/DBIntegral.php";
	
	require_once "$library/org.hailong.integral/tasks/IntegralTask.php";
	require_once "$library/org.hailong.integral/tasks/IntegralAuthTask.php";
	require_once "$library/org.hailong.integral/tasks/IntegralIncomeTask.php";
	require_once "$library/org.hailong.integral/tasks/IntegralTotalTask.php";
	require_once "$library/org.hailong.integral/tasks/IntegralFetchTask.php";
	
	require_once "$library/org.hailong.integral/services/IntegralService.php";
}
?>