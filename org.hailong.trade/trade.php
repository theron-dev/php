<?php

/**
 * 交易订单
 */

if($library){
	
	define("DB_TRADE","trade");
	define("TRADE_ALIAS_ADMIN","org/hailong/trade/admin");
	
	require_once "$library/org.hailong.configs/error_code.php";
	require_once "$library/org.hailong.log/log.php";
	require_once "$library/org.hailong.service/service.php";
	require_once "$library/org.hailong.product/product.php";
	
	require_once "$library/org.hailong.trade/TradeException.php";
	
	require_once "$library/org.hailong.trade/db/DBTrade.php";
	
	require_once "$library/org.hailong.trade/tasks/TradeTask.php";
	require_once "$library/org.hailong.trade/tasks/TradeAuthTask.php";
	require_once "$library/org.hailong.trade/tasks/TradeCancelTask.php";
	require_once "$library/org.hailong.trade/tasks/TradeConfirmTask.php";
	require_once "$library/org.hailong.trade/tasks/TradeCreateTask.php";
	require_once "$library/org.hailong.trade/tasks/TradePaymentTask.php";
	require_once "$library/org.hailong.trade/tasks/TradeShippingTask.php";
	
	require_once "$library/org.hailong.trade/tasks/TradeRefundTask.php";
	require_once "$library/org.hailong.trade/tasks/TradeRefundCancelTask.php";
	require_once "$library/org.hailong.trade/tasks/TradeRefundCloseTask.php";
	
	require_once "$library/org.hailong.trade/tasks/TradeGetProductCountTask.php";
	
	require_once "$library/org.hailong.trade/services/TradeService.php";
}
?>