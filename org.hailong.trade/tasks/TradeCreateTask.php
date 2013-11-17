<?php

/**
 * 创建交易
 * @author zhanghailong
 *
 */
class TradeCreateTask extends TradeAuthTask{

	/**
	 *　交易ID 输出
	 * @var int
	 */
	public $tid;
	/**
	* 商品所有者ID
	* @var int
	*/
	public $puid;
	/**
	 * 类型
	 * @var int
	 */
	public $type;
	/**
	 * 商品ID
	 * @var int
	 */
	public $pid;
	/**
	 * 单价
	 * @var float
	 */
	public $unitPrice;
	/**
	 * 数量
	 * @var int
	 */
	public $count;
	
	/**
	 * 内容
	 * @var Object
	 */
	public $body;
	
}

?>