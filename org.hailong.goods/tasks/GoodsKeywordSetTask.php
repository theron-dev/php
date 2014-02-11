<?php

/**
* 物品关键字设置任务
* @author zhanghailong
*
*/
class GoodsKeywordSetTask extends GoodsAuthTask{
	
	/**
	 * 物品ID
	 * @var int
	 */
	public $gid;
	/**
	 * 关键字
	 * @var String
	 */
	public $keyword;
	
	/**
	 * 输出　物品详情
	 * @var DBGoods
	 */
	public $results;
	
}

?>