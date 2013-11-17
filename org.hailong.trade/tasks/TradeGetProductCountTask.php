<?php

/**
 * 获取已购买商品数
 * @author zhanghailong
 *
 */
class TradeGetProductCountTask extends TradeAuthTask{

	/**
	 * 商品ID
	 * @var int
	 */
	public $pid;
	
	/**
	 * 数量 输出
	 * @var int
	 */
	public $count;
	
	
}

?>