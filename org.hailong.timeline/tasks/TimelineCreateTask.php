<?php

/**
* 时间线创建　任务
* @author zhanghailong
*
*/

class TimelineCreateTask extends TimelineTask{
	
	/**
	 * 实体类型
	 * @var int
	 */
	public $etype;
	/**
	 * 实体ID
	 * @var int
	 */
	public $eid;
	/**
	 * 时间戳
	 * @var int
	 */
	public $timestamp;
	
	/**
	 * 输出
	 * @var DBTimeline;
	 */
	public $results;
}

?>