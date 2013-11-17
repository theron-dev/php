<?php

/**
 * 取消退款
 * @author zhanghailong
 *
 */
class TradeRefundCancelTask extends TradeAuthTask{

	/**
	 *　交易ID
	 * @var int
	 */
	public $tid;
	
	/**
	* 状态 输出
	* @var int
	*/
	public $state;
}

?>