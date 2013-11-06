<?php

/**
 * 权限管理组件
 */
if($library){
	require_once "$library/org.hailong.authority/authority.php";
	
	require_once "$library/org.hailong.authority.admin/tasks/AuthorityAdminTask.php";
	
	require_once "$library/org.hailong.authority.admin/tasks/AuthorityRoleAddTask.php";
	require_once "$library/org.hailong.authority.admin/tasks/AuthorityRoleRemoveTask.php";
	require_once "$library/org.hailong.authority.admin/tasks/AuthorityRoleUpdateTask.php";
	
	require_once "$library/org.hailong.authority.admin/services/AuthorityRoleAdminService.php";

	require_once "$library/org.hailong.authority.admin/tasks/AuthorityEntityAddTask.php";
	require_once "$library/org.hailong.authority.admin/tasks/AuthorityEntityRemoveTask.php";
	require_once "$library/org.hailong.authority.admin/tasks/AuthorityEntityUpdateTask.php";
	
	require_once "$library/org.hailong.authority.admin/services/AuthorityEntityAdminService.php";
	
	
	require_once "$library/org.hailong.authority.admin/tasks/AuthorityAddTask.php";
	require_once "$library/org.hailong.authority.admin/tasks/AuthorityRemoveTask.php";
	
	require_once "$library/org.hailong.authority.admin/services/AuthorityAdminService.php";
}
?>