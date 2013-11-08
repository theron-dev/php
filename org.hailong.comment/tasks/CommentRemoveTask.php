<?php

/**
* 删除评论任务
* @author zhanghailong
*
*/
class CommentRemoveTask extends CommentAuthTask{
	
	/**
	* 用户ID　为null时使用内部参数 auth
	* @var int
	*/
	public $uid;
	/**
	 * 评论ID
	 * @var int
	 */
	public $cid;
	
	/**
	 * 输出
	 * @var boolean
	 */
	public $results;

}

?>