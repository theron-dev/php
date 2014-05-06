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
	 * 状态
	 * @var int
	 */
	public $status;
	/**
	 * 备注
	 * @var String
	 */
	public $remark;

	/**
	 *
	 * @var O2ODBTradeOrder
	 */
	public $results;
}

?>