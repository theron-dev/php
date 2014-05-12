<?php

class CounterPullTask extends CounterAuthTask{

	/**
	 * 目标
	 * @var String
	 */
	public $targets;
	
	/**
	 * 最后ID
	 * @var int
	 */
	public $lastId;
	
	/**
	 * 
	 * @var array
	 */
	public $results;
}

?>