<?php

class CounterAddTask extends CounterAuthTask{

	/**
	 * 目标用户ID
	 * @var int
	 */
	public $tuid;
	/**
	 * 目标
	 * @var String
	 */
	public $target;
	/**
	 * 目标ID
	 * @var int
	 */
	public $tid;

	/**
	 * 来源类型
	 * @var int
	 */
	public $ftype;
	/**
	 * 来源ID
	 * @var int
	 */
	public $fid;

	/**
	 * 数量
	 * @var int
	 */
	public $count;
	
	/**
	 * 
	 * @var DBCounter
	 */
	public $results;
}

?>