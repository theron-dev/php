<?php

/**
 * 权限组件
 */
if($library){
	
	require_once "$library/org.hailong.configs/error_code.php";
	require_once "$library/org.hailong.account/account.php";
	require_once "$library/org.hailong.log/log.php";
	require_once "$library/org.hailong.cache/cache.php";
	
	define("DB_AUTHORITY","authority");
	
	require_once "$library/org.hailong.authority/AuthorityException.php";
	require_once "$library/org.hailong.authority/db/DBAuthority.php";
	require_once "$library/org.hailong.authority/db/DBAuthorityRole.php";
	require_once "$library/org.hailong.authority/db/DBAuthorityEntity.php";
	
	require_once "$library/org.hailong.authority/tasks/AuthorityRoleValidateTask.php";
	require_once "$library/org.hailong.authority/tasks/AuthorityEntityValidateTask.php";
	require_once "$library/org.hailong.authority/tasks/AuthorityLogTask.php";
	
	require_once "$library/org.hailong.authority/services/AuthorityValidateService.php";
}
?>