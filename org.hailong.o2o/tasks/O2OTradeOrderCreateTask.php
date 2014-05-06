<?php

/**
 * 创建交易订单
 * @author hailongz
 *
 */
class O2OTradeOrderCreateTask extends AuthTask{

	/**
	 *　交易实体ID
	 * @var int
	 */
	public $eid;
	
	/**
	 * 属性
	 * @var array
	 */
	public $propertys;

	/**
	 *
	 * @var O2ODBTradeOrder
	 */
	public $results;
}

?>