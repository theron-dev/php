<?php

/**
 * 设备
 */
if($library){
	
	require_once "$library/org.hailong.configs/error_code.php";
	
	define("DB_DEVICE","device");
	
	require_once "$library/org.hailong.device/DeviceException.php";
	require_once "$library/org.hailong.device/db/DBDevice.php";
	
	require_once "$library/org.hailong.device/services/DeviceAuthService.php";
	
	require_once "$library/org.hailong.device/tasks/DeviceAuthTask.php";
}
?>