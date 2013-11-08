<?php

/**
* 取消喜欢任务
* @author zhanghailong
*
*/
class UnLikeTask extends LikedAuthTask{
	
	/**
	 * 用户ID 为 null 时使用内部参数 auth.
	 * @var int
	 */
	public $uid;
	/**
	 * 喜欢实体类型
	 * @var int
	 */
	public $etype;
	/**
	 * 喜欢实体ID
	 * @var int
	 */
	public $eid;
	/**
	 * 是否成功
	 * @var boolean
	 */
	public $results;
}

?>