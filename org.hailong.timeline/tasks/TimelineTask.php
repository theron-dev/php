<?php

/**
* 时间线任务
* @author zhanghailong
*
*/

class TimelineTask extends AuthTask{
	
	/**
	 * 用户ID　null时使用内部参数 auth
	 * @var int
	 */
	public $uid;
	
	/**
	 * 内部数据上下文
	 * @var DBContext
	 */
	public $dbContext;
	
	public function prefix(){
		return "timeline";
	}
}

?>