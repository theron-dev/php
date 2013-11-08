<?php

/**
* 标签匹配任务
* @author zhanghailong
*
*/

class TagMatchTask extends TagTask{
	
	/**
	 * 匹配内容
	 * @var String
	 */
	public $body;
	
	/**
	 * 增量
	 * @var int
	 */
	public $inc;
	
	/**
	 * 输出
	 * @var array(tid1,tid2,...)
	 */
	public $results;
}

?>