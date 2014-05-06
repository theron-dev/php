<?php

	if($library){
		
		define("DB_O2O","o2o");
		
		require_once "$library/org.hailong.o2o/db/O2ODBProvider.php";
		require_once "$library/org.hailong.o2o/db/O2ODBTradeEntity.php";
		require_once "$library/org.hailong.o2o/db/O2ODBTradeOrder.php";
		require_once "$library/org.hailong.o2o/db/O2ODBTradeOrderStatus.php";
		require_once "$library/org.hailong.o2o/tasks/O2OTask.php";
		require_once "$library/org.hailong.o2o/tasks/O2OAuthTask.php";
		require_once "$library/org.hailong.o2o/tasks/O2OTradeEntityCreateTask.php";
		require_once "$library/org.hailong.o2o/tasks/O2OTradeEntityGetTask.php";
		require_once "$library/org.hailong.o2o/tasks/O2OTradeEntityRemoveTask.php";
		require_once "$library/org.hailong.o2o/tasks/O2OTradeOrderCreateTask.php";
		require_once "$library/org.hailong.o2o/tasks/O2OTradeOrderStatusSetTask.php";
		require_once "$library/org.hailong.o2o/services/O2OService.php";
		
	}
	
?>