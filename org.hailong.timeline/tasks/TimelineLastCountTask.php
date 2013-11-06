<?php

/**
* 最后加入数　任务
* @author zhanghailong
*
*/

class TimelineLastCountTask extends TimelineTask{
	
	/**
	 * 实体类型
	 * @var array(etype1,etype2)
	 */
	public $etypes;

	/**
	 * 启始ID
	 * @var int
	 */
	public $beginTlid;
	
	
	/**
	 * 输出
	 * @var int
	 */
	public $results;
}

?>