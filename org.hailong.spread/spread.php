<?php

/**
 * 推广
 */
if($library){
	
	define("DB_SPREAD","spread");

	
	require_once "$library/org.hailong.configs/error_code.php";
	require_once "$library/org.hailong.account/account.php";
	require_once "$library/org.hailong.authority/authority.php";
	
	require_once "$library/org.hailong.spread/SpreadException.php";
	require_once "$library/org.hailong.spread/db/DBSpread.php";

	require_once "$library/org.hailong.spread/tasks/SpreadTask.php";
	require_once "$library/org.hailong.spread/tasks/SpreadAuthTask.php";
	require_once "$library/org.hailong.spread/tasks/SpreadAskTask.php";
	require_once "$library/org.hailong.spread/tasks/SpreadCreateTask.php";
	require_once "$library/org.hailong.spread/tasks/SpreadRemoveTask.php";
	
	require_once "$library/org.hailong.spread/services/SpreadService.php";
}
?>