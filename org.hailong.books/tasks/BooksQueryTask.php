<?php

/**
 * 
 * @author zhanghailong
 *
 */
class BooksQueryTask extends BooksAuthTask{
	
	/**
	 * 开始时间
	 * @var int
	 */
	public $startTime;
	
	/**
	 * 结束时间
	 * @var int
	 */
	public $endTime;
	
	/**
	 * 输出
	 * @var array(DBBooks)
	 */
	public $results;
	
}

?>