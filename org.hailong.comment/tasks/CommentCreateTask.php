<?php

/**
* 创建评论任务
* @author zhanghailong
*
*/
class CommentCreateTask extends CommentAuthTask{
	
	/**
	 * 用户ID　为null时使用内部参数 auth
	 * @var int
	 */
	public $uid;
	/**
	 * 内容
	 * @var String
	 */
	public $body;
	/**
	 * 目标用户ID
	 * @var int
	 */
	public $tuid;
	/**
	 * 目标类型
	 * @var int
	 */
	public $etype;
	/**
	 * 目标ID
	 * @var int
	 */
	public $eid;
	/**
	 * 目标类型
	 * @var int
	 */
	public $ttype;
	/**
	 * 目标ID
	 * @var int
	 */
	public $tid;
	/**
	* 来源
	* @var int
	*/
	public $source;
	
	/**
	 * 输出	
	 * @var DBComment;
	 */
	public $results;
	
	public function __construct(){
		$this->source = DBCommentSourceNone;
	}

}

?>