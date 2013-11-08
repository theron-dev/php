<?php

/**
* 用户的喜欢数任务
* @author zhanghailong
*
*/
class LikedCountTask extends LikedTask{
	
	/**
	 * 用户ID 为 null 时使用内部参数 auth.
	 * @var int
	 */
	public $uid;
	/**
	 * 实体类型
	 * @var array()
	 */
	public $etypes;
	/**
	 * 输出 
	 * @var int
	 */
	public $results;
}

?>