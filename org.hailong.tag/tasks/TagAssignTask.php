<?php

/**
* 访问标签
* @author zhanghailong
*
*/

class TagAssignTask extends TagTask{
	
	/**
	 * 标签
	 * @var String
	 */
	public $tag;
	/**
	 * 标签ID 输出
	 * @var int
	 */
	public $tid;
	/**
	 * 增量
	 * @var int
	 */
	public $inc;
	
	public function __construct(){
		$this->inc = 0;
	}
}

?>