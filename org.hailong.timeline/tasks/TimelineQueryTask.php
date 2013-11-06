<?php

/**
* 查询　任务
* @author zhanghailong
*
*/

class TimelineQueryTask extends TimelineTask{
	
	/**
	 * 实体类型
	 * @var array(etype1,etype2,...)
	 */
	public $etypes;
	/**
	 * 最小ID
	 * @var int
	 */
	public $minTlid;
	/**
	 * 最大ID
	 * Enter description here ...
	 * @var unknown_type
	 */
	public $maxTlid;
	/**
	 * 偏移数量
	 * @var int
	 */
	public $offset;
	/**
	 * 限制数量
	 * @var int
	 */
	public $limit;
	
	/**
	 * 输出
	 * @var array(DBTimeline,DBTimeline,...);
	 */
	public $results;
	
	public function __construct(){
		$this->offset = 0;
		$this->limit = 50;
	}
}

?>