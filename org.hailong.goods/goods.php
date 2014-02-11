<?php

/**
 * 物品
 */
if($library){
	
	define("DB_GOODS","goods");
	
	define("CACHE_GOODS_BROWSER","goods/browser");
	
	require_once "$library/org.hailong.configs/error_code.php";
	

	require_once "$library/org.hailong.uri/uri.php";
	require_once "$library/org.hailong.spread/spread.php";
	
	require_once "$library/org.hailong.goods/GoodsException.php";
	
	require_once "$library/org.hailong.goods/db/DBGoods.php";
	require_once "$library/org.hailong.goods/db/DBGoodsClassify.php";
	require_once "$library/org.hailong.goods/db/DBGoodsKeyword.php";
	require_once "$library/org.hailong.goods/db/DBGoodsImage.php";
	
	require_once "$library/org.hailong.goods/tasks/GoodsTask.php";
	require_once "$library/org.hailong.goods/tasks/GoodsAuthTask.php";
	
	require_once "$library/org.hailong.goods/tasks/GoodsCreateTask.php";
	require_once "$library/org.hailong.goods/tasks/GoodsImportTask.php";
	require_once "$library/org.hailong.goods/tasks/GoodsQueryTask.php";
	require_once "$library/org.hailong.goods/tasks/GoodsForwardTask.php";
	require_once "$library/org.hailong.goods/tasks/GoodsLikeTask.php";
	require_once "$library/org.hailong.goods/tasks/GoodsSpreadAskTask.php";
	require_once "$library/org.hailong.goods/tasks/GoodsResetClassifyTask.php";
	require_once "$library/org.hailong.goods/tasks/GoodsAllResetClassifyTask.php";
	require_once "$library/org.hailong.goods/tasks/GoodsBrowserTask.php";
	require_once "$library/org.hailong.goods/tasks/GoodsGetTask.php";
	require_once "$library/org.hailong.goods/tasks/GoodsTagsTask.php";
	require_once "$library/org.hailong.goods/tasks/GoodsKeywordSetTask.php";
	
	require_once "$library/org.hailong.goods/services/GoodsService.php";
	require_once "$library/org.hailong.goods/services/GoodsSpreadService.php";
}
?>