<?php

/**
 * 
 * @author zhanghailong
 *
 */
class TopSearchTask extends TopTask{
	
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

	public $pageIndex;
	public $pageSize;
	
	/**
	 * 输出
	 * @var array(DBTop)
	 */
	public $results;
	
}

?>