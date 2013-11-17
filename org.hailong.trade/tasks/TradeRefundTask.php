<?php

/**
 * 退款
 * @author zhanghailong
 *
 */
class TradeRefundTask extends TradeAuthTask{

	/**
	 *　交易ID
	 * @var int
	 */
	public $tid;
	
	/**
	 * 退款金额
	 * @var double
	 */
	public $price;
	
	/**
	* 状态 输出
	* @var int
	*/
	public $state;
}

?>