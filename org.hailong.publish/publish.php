<?php

/**
 * 发布系统
 */
if($library){
	

	
	define("DB_PUBLISH","publish");
	
	require_once "$library/org.hailong.configs/error_code.php";

	require_once "$library/org.hailong.publish/PublishException.php";
	
	require_once "$library/org.hailong.publish/db/DBPublishDomain.php";
	require_once "$library/org.hailong.publish/db/DBPublishSchema.php";
	require_once "$library/org.hailong.publish/db/DBPublishSchemaRuntime.php";
	
	require_once "$library/org.hailong.publish/tasks/IPublishDataSource.php";
	require_once "$library/org.hailong.publish/tasks/PublishTask.php";
	require_once "$library/org.hailong.publish/tasks/PublishAuthTask.php";
	require_once "$library/org.hailong.publish/tasks/PublishLockTask.php";
	require_once "$library/org.hailong.publish/tasks/PublishUnlockTask.php";
	require_once "$library/org.hailong.publish/tasks/PublishPutTask.php";
	require_once "$library/org.hailong.publish/tasks/PublishCreateTask.php";
	
	require_once "$library/org.hailong.publish/services/PublishService.php";
	
	
}
?>