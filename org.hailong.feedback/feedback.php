<?php

/**
 * 反馈
 */

if($library){
	
	define("DB_FEEDBACK","feedback");
	
	require_once "$library/org.hailong.configs/error_code.php";
	require_once "$library/org.hailong.service/service.php";
	
	require_once "$library/org.hailong.feedback/db/DBFeedback.php";
	
	require_once "$library/org.hailong.feedback/tasks/FeedbackTask.php";
	
	require_once "$library/org.hailong.feedback/services/FeedbackService.php";
	
}
?>