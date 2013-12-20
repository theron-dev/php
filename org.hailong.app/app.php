<?php

/**
 * 排队系统
 */
if($library){
	
	define("DB_APP","app");
	
	require_once "$library/org.hailong.configs/error_code.php";
	
	require_once "$library/org.hailong.app/AppException.php";
	require_once "$library/org.hailong.app/db/DBApp.php";
	require_once "$library/org.hailong.app/db/DBAppAuth.php";
	require_once "$library/org.hailong.app/db/DBAppVersion.php";
	require_once "$library/org.hailong.app/db/DBAppDevice.php";
	
	require_once "$library/org.hailong.app/tasks/AppAuthTask.php";
	require_once "$library/org.hailong.app/tasks/AppVersionTask.php";
	require_once "$library/org.hailong.app/tasks/AppDeviceTask.php";
	require_once "$library/org.hailong.app/tasks/AppDevicePushTask.php";
	require_once "$library/org.hailong.app/tasks/AppCreateTask.php";
	require_once "$library/org.hailong.app/tasks/AppUpdateTask.php";
	require_once "$library/org.hailong.app/tasks/AppRemoveTask.php";
	require_once "$library/org.hailong.app/tasks/AppUserPushTask.php";
	require_once "$library/org.hailong.app/tasks/AppAuthRemoveTask.php";
	require_once "$library/org.hailong.app/tasks/AppAutoAuthTask.php";
	
	require_once "$library/org.hailong.app/tasks/AppVersionCreateTask.php";
	require_once "$library/org.hailong.app/tasks/AppVersionUpdateTask.php";
	require_once "$library/org.hailong.app/tasks/AppVersionRemoveTask.php";
	require_once "$library/org.hailong.app/tasks/AppVersionSetLastTask.php";
	
	require_once "$library/org.hailong.app/services/AppAuthService.php";
	require_once "$library/org.hailong.app/services/AppVersionService.php";
	require_once "$library/org.hailong.app/services/AppDeviceService.php";
	require_once "$library/org.hailong.app/services/AppDevicePushService.php";
	require_once "$library/org.hailong.app/services/AppAdminService.php";
	require_once "$library/org.hailong.app/services/AppUserPushService.php";
}
?>