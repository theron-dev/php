<?php
/**
* 服务
*/
if($library){

	require_once "$library/org.hailong.functions/functions.php";
	
	require_once "$library/org.hailong.db/db.php";
	
	require_once "$library/org.hailong.service/define/IService.php";
	require_once "$library/org.hailong.service/define/IServiceContainer.php";
	require_once "$library/org.hailong.service/define/IServiceContext.php";
	require_once "$library/org.hailong.service/define/ITask.php";
	require_once "$library/org.hailong.service/define/ServiceException.php";
	require_once "$library/org.hailong.service/define/Service.php";
	require_once "$library/org.hailong.service/define/ServiceContainerManager.php";
	require_once "$library/org.hailong.service/define/ServiceContext.php";
	
	require_once "$library/org.hailong.service/tasks/DBContextTask.php";
	require_once "$library/org.hailong.service/services/DBService.php";
	
	require_once "$library/org.hailong.service/tasks/UnionTask.php";
	require_once "$library/org.hailong.service/services/UnionService.php";
}
?>