<?php

/**
 * 排队系统
 */
if($library){
	
	define("DB_TAG","tag");
	
	require_once "$library/org.hailong.tag/db/DBTag.php";
	
	require_once "$library/org.hailong.tag/tasks/TagTask.php";
	require_once "$library/org.hailong.tag/tasks/TagAssignTask.php";
	require_once "$library/org.hailong.tag/tasks/TagMatchTask.php";
	require_once "$library/org.hailong.tag/tasks/TagTopTask.php";
	
	require_once "$library/org.hailong.tag/services/TagService.php";
}
?>