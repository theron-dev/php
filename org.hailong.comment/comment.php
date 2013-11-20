<?php

/**
 * 评论
 */
if($library){
	
	define("DB_COMMENT","comment");
	
	define("COMMENT_ADMIN_AUTH_ALIAS","comment/admin");
	
	
	require_once "$library/org.hailong.configs/error_code.php";
	
	require_once "$library/org.hailong.comment/CommentException.php";

	require_once "$library/org.hailong.comment/db/DBComment.php";
	
	require_once "$library/org.hailong.comment/tasks/CommentTask.php";
	require_once "$library/org.hailong.comment/tasks/CommentAuthTask.php";
	require_once "$library/org.hailong.comment/tasks/CommentCreateTask.php";
	require_once "$library/org.hailong.comment/tasks/CommentRemoveTask.php";
	require_once "$library/org.hailong.comment/tasks/CommentQueryTask.php";
	require_once "$library/org.hailong.comment/tasks/CommentGetTask.php";
	
	require_once "$library/org.hailong.comment/services/CommentService.php";

}
?>