<?php

/**
 * 消息获取任务
 * @author zhanghailong
 *
 */
class MessageQueryTask extends MessageTask{
	
	public $maxMid;
	public $minMid;
	public $limit;
	
	public $orderBy;
	
	/**
	 * 输出
	 * @var array(DBMessage,...)
	 */
	public $results;
	
	
	public function __construct(){
		$this->orderBy = "mid ASC";
	}
}

?>