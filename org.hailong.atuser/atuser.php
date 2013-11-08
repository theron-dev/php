<?php

/**
 * @用户
 */
if($library){
	
	define("DB_ATUSER","atuser");

	require_once "$library/org.hailong.configs/error_code.php";
	
	require_once "$library/org.hailong.atuser/ATUserException.php";

	require_once "$library/org.hailong.atuser/db/DBATUser.php";
	
	require_once "$library/org.hailong.atuser/tasks/ATUserTask.php";
	require_once "$library/org.hailong.atuser/tasks/ATUserAuthTask.php";
	require_once "$library/org.hailong.atuser/tasks/ATUserCreateTask.php";
	require_once "$library/org.hailong.atuser/tasks/ATUserRemoveTask.php";
	require_once "$library/org.hailong.atuser/tasks/ATUserBodyTask.php";
	require_once "$library/org.hailong.atuser/tasks/ATUserFetchTask.php";
	
	require_once "$library/org.hailong.atuser/services/ATUserService.php";

}
?>