<?php

/**
 * sms
 */
if($library){
	
	require_once "$library/org.hailong.configs/error_code.php";
	require_once "$library/org.hailong.log/log.php";
	
	
	require_once "$library/org.hailong.sms/SMSException.php";
	require_once "$library/org.hailong.sms/tasks/SMSSendTask.php";
	require_once "$library/org.hailong.sms/services/SMSService.php";
	
}
?>