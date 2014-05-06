<?php

/**
 * 获取交易实体
 * @author hailongz
 *
 */
class O2OTradeEntityGetTask extends AuthTask{

	/**
	 *　交易实体ID
	 * @var int
	 */
	public $eid;
	
	/**
	 *　服务者ID, eid === null 时使用pid
	 * @var int
	 */
	public $pid;

	/**
	 *
	 * @var O2ODBTradeEntity
	 */
	public $results;
}

?>