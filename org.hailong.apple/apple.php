<?php

/**
 * Apple
 */
if($library){
	
	define("DB_APPLE","apple");
	
	require_once "$library/org.hailong.configs/error_code.php";
	require_once "$library/org.hailong.apple/AppleException.php";
	require_once "$library/org.hailong.apple/db/DBApplePurchase.php";
	require_once "$library/org.hailong.apple/tasks/ApplePushTask.php";
	require_once "$library/org.hailong.apple/tasks/ApplePurchaseTask.php";
	require_once "$library/org.hailong.apple/services/ApplePushService.php";
	require_once "$library/org.hailong.apple/services/ApplePurchaseService.php";
}
?>