<?php

/**
 * 推广索取任务
 * @author zhanghailong
 *
 */
class SpreadAskTask extends SpreadTask{
	
	/**
	 * 推广类型
	 * @var DBSpreadType
	 */
	public $type;
	/**
	 * 限制数
	 * @var int
	 */
	public $limit;
	
	/**
	 * 输出　
	 * @var array(DBSpread,DBSpread,...)
	 */
	public $results;
	
	public function __construct(){
		$this->limit = 1;
		$this->type = DBSpreadTypeTaoke;
	}
}

?>