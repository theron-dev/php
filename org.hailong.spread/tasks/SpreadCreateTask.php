<?php

/**
 * 推广创建任务
 * @author zhanghailong
 *
 */
class SpreadCreateTask extends SpreadAuthTask{
	
	/**
	 * 推广类型
	 * @var DBSpreadType
	 */
	public $type;
	/**
	 * 推广名称
	 * @var String
	 */
	public $title;
	/**
	 * 推广标示
	 * @var String
	 */
	public $marked;
	/**
	 * 推广限制 0 为不限制　
	 * @var int
	 */
	public $askLimit;
	
	/**
	 * 输出
	 * @var DBSpread
	 */
	public $results;

}

?>