<?php

/**
 * 取消交易
 * @author zhanghailong
 *
 */
class TradeCancelTask extends TradeAuthTask{

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