<?php

/**
* 前N标签任务
* @author zhanghailong
*
*/

class TagTopTask extends TagTask{
	
	/**
	 * 数量限制
	 * @var int
	 */
	public $limit;
	
	/**
	 * 输出
	 * @var array(tag,tag,...)
	 */
	public $results;
	

	public function __construct(){
		$this->limit = 20;
	}
}

?>