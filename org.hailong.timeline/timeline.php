<?php

/**
 * 时间线
 */
if($library){
	
	
	require_once "$library/org.hailong.configs/error_code.php";
	require_once "$library/org.hailong.timeline/TimelineException.php";
	require_once "$library/org.hailong.timeline/db/DBTimeline.php";
	
	require_once "$library/org.hailong.timeline/tasks/TimelineTask.php";
	require_once "$library/org.hailong.timeline/tasks/TimelineCreateTask.php";
	require_once "$library/org.hailong.timeline/tasks/TimelineRemoveTask.php";
	require_once "$library/org.hailong.timeline/tasks/TimelineQueryTask.php";
	require_once "$library/org.hailong.timeline/tasks/TimelineLastCountTask.php";
	require_once "$library/org.hailong.timeline/tasks/TimelineMaxTask.php";
	
	require_once "$library/org.hailong.timeline/services/TimelineService.php";
	
}
?>