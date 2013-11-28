<?php

/**
 * 商品
 */

if($library){
	
	define("DB_PRODUCT","product");
	define("PRODUCT_ALIAS_ADMIN","org/hailong/product/admin");
	
	require_once "$library/org.hailong.configs/error_code.php";
	require_once "$library/org.hailong.log/log.php";
	require_once "$library/org.hailong.service/service.php";
	
	require_once "$library/org.hailong.product/ProductException.php";
	
	require_once "$library/org.hailong.product/db/DBProduct.php";
	
	require_once "$library/org.hailong.product/tasks/ProductTask.php";
	require_once "$library/org.hailong.product/tasks/ProductAuthTask.php";
	require_once "$library/org.hailong.product/tasks/ProductCreateTask.php";
	require_once "$library/org.hailong.product/tasks/ProductPublishTask.php";
	require_once "$library/org.hailong.product/tasks/ProductRemoveTask.php";
	require_once "$library/org.hailong.product/tasks/ProductTradeTask.php";
	require_once "$library/org.hailong.product/tasks/ProductUnpublishTask.php";
	require_once "$library/org.hailong.product/tasks/ProductUntradeTask.php";
	require_once "$library/org.hailong.product/tasks/ProductUpdateTask.php";
	require_once "$library/org.hailong.product/tasks/ProductGetForUpdateTask.php";
	require_once "$library/org.hailong.product/tasks/ProductGetTask.php";
	
	require_once "$library/org.hailong.product/services/ProductService.php";
	
}
?>