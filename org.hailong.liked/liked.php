<?php

/**
 * 喜欢
 */
if($library){
	
	define("DB_LIKED","liked");
	
	require_once "$library/org.hailong.configs/error_code.php";
	
	require_once "$library/org.hailong.liked/LikedException.php";
	
	require_once "$library/org.hailong.liked/db/DBLiked.php";
	require_once "$library/org.hailong.liked/tasks/LikedAuthTask.php";
	require_once "$library/org.hailong.liked/tasks/LikeTask.php";
	require_once "$library/org.hailong.liked/tasks/UnLikeTask.php";
	require_once "$library/org.hailong.liked/tasks/LikeRemoveTask.php";
	require_once "$library/org.hailong.liked/tasks/LikeQueryTask.php";
	require_once "$library/org.hailong.liked/tasks/LikeCheckTask.php";
	require_once "$library/org.hailong.liked/tasks/LikedTask.php";
	require_once "$library/org.hailong.liked/tasks/LikedCountTask.php";
	require_once "$library/org.hailong.liked/tasks/LikeUserQueryTask.php";
	
	require_once "$library/org.hailong.liked/services/LikedService.php";
}
?>