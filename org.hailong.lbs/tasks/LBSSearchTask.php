<?php

/**
 * 
 * @author zhanghailong
 *
 */
class LBSSearchTask extends LBSSourceUpdateTask{
	
	/**
	 * 限制距离 米
	 * @var int
	 */
	public $distance;
	
	public $pageIndex;
	public $pageSize;
	
	/**
	 * 输出	
	 * @var array(DBLBSSearch,)
	 */
	public $results;
}

?>