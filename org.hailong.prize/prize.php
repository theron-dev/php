<?php

/**
 * 奖品
 */
if($library){
	
	define("DB_PRIZE","prize");
	
	define("PrizeProductEntityType",10);
	
	define("PrizeAdminAuthorityEntity","prize/admin");
	
	require_once "$library/org.hailong.configs/error_code.php";
	require_once "$library/org.hailong.product/product.php";
	require_once "$library/org.hailong.trade/trade.php";
	require_once "$library/org.hailong.coin/coin.php";
	
	require_once "$library/org.hailong.prize/db/DBPrize.php";
	require_once "$library/org.hailong.prize/db/DBPrizeImage.php";
	
	require_once "$library/org.hailong.prize/tasks/PrizeTask.php";
	require_once "$library/org.hailong.prize/tasks/PrizeAuthTask.php";
	require_once "$library/org.hailong.prize/tasks/PrizeBuyTask.php";
	require_once "$library/org.hailong.prize/tasks/PrizeCreateTask.php";
	require_once "$library/org.hailong.prize/tasks/PrizeRemoveTask.php";
	
	require_once "$library/org.hailong.prize/services/PrizeService.php";
	
}
?>