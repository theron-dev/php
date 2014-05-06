<?php

/**
 * 创建交易实体
 * @author hailongz
 *
 */
class O2OTradeEntityCreateTask extends AuthTask{

	/**
	 *　服务者ID
	 * @var int
	 */
	public $pid;
	
	/**
	 * 属性
	 * @var array
	 */
	public $propertys;
	
	/**
	 * 
	 * @var O2ODBTradeEntity
	 */
	public $results;
}

?>