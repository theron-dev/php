<?php

/**
 * 分类
 */
if($library){
	
	define("DB_CLASSIFY","classify");
	
	define("SYNC_ETYPE_CLASSIFY",80);
	
	require_once "$library/org.hailong.configs/error_code.php";
	require_once "$library/org.hailong.tag/tag.php";
	
	require_once "$library/org.hailong.classify/ClassifyException.php";
	require_once "$library/org.hailong.classify/db/DBClassify.php";
	require_once "$library/org.hailong.classify/db/DBClassifyKeyword.php";
	
	require_once "$library/org.hailong.classify/tasks/ClassifyTask.php";
	require_once "$library/org.hailong.classify/tasks/ClassifyCreateTask.php";
	require_once "$library/org.hailong.classify/tasks/ClassifyRemoveTask.php";
	require_once "$library/org.hailong.classify/tasks/ClassifyUpdateTask.php";
	require_once "$library/org.hailong.classify/tasks/ClassifyQueryTask.php";
	require_once "$library/org.hailong.classify/tasks/ClassifyParentTask.php";
	require_once "$library/org.hailong.classify/tasks/ClassifyMatchTask.php";
	require_once "$library/org.hailong.classify/tasks/ClassifyChildTask.php";
	require_once "$library/org.hailong.classify/tasks/ClassifyKeywordAssignTask.php";
	require_once "$library/org.hailong.classify/tasks/ClassifyQueryTopTask.php";
	
	require_once "$library/org.hailong.classify/services/ClassifyService.php";
	
}
?>