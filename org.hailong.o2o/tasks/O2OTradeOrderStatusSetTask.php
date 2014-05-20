<?php

/**
 * 设置交易订单状态
 * @author hailongz
 *
 */
class O2OTradeOrderStatusSetTask extends AuthTask{

	/**
	 *　交易订单ID
	 * @var int
	 */
	public $oid;
	
	/**
	 * 
	 * @var O2ODBTradeOrder
	 */
	public $order;
	
	/**
	 * 状态
	 * @var int
	 */
	public $status;
	/**
	 * 备注
	 * @var String
	 */
	public $remark;

}

?>