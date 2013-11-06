<?php

/**
 * 统计任务
 * @author zhanghailong
 *
 */
class StatisticsTask implements ITask{
	
	/**
	 * 统计目标
	 * @var String
	 */
	public $target;
	/**
	 * 信息来源
	 * @var String
	 */
	public $source;
	/**
	 * 统计标示
	 * @var String
	 */
	public $key;
	/**
	 * 统计计数
	 * @var int
	 */
	public $count;
	/**
	* 归类时间
	* @var int
	*/
	public $classifyTime;
	
	public function prefix(){
		return "statistics";
	}
	
	public function __construct(){
		$this->count = 1;
		$this->classifyTime = intval( time() / (24 * 3600)) * 24 * 3600;
		$this->source = isset($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:"";
	}
}

?>