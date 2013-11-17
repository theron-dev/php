<?php

/**
 * 关闭退款
 * @author zhanghailong
 *
 */
class TradeRefundCloseTask extends TradeAuthTask{

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