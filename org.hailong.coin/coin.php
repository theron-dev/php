<?php

/**
 * 金币
 */

if($library){
	
	define("DB_COIN","coin");
	define("COIN_ALIAS_ADMIN","org/hailong/coin/admin");
	
	require_once "$library/org.hailong.configs/error_code.php";
	require_once "$library/org.hailong.log/log.php";
	require_once "$library/org.hailong.service/service.php";
	
	require_once "$library/org.hailong.coin/CoinException.php";
	
	require_once "$library/org.hailong.coin/db/DBCoin.php";
	require_once "$library/org.hailong.coin/db/DBCoinIncome.php";
	
	require_once "$library/org.hailong.coin/tasks/CoinTask.php";
	require_once "$library/org.hailong.coin/tasks/CoinAuthTask.php";
	require_once "$library/org.hailong.coin/tasks/CoinGetTask.php";
	require_once "$library/org.hailong.coin/tasks/CoinIncomeTask.php";
	
	require_once "$library/org.hailong.coin/services/CoinService.php";
	
}
?>