<?php

/**
 * 
 * @author zhanghailong
 *
 */
class TopItemTask extends TopTask{
	
	/**
	 * 标示
	 * @var String
	 */
	public $key;
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
	 * 热度
	 * @var int
	 */
	public $topCount;
	
	/**
	 * 限制时间 默认24小时
	 * @var int
	 */
	public $limitTime;

}

?>