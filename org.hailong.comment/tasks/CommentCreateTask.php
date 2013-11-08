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
	 * 父级评论ID 与 etype eid 选添
	 * @var int
	 */
	public $pcid;
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
	* 来源
	* @var int
	*/
	public $source;
	
	/**
	 * 输出	
	 * @var DBComment;
	 */
	public $resutls;
	
	public function __construct(){
		$this->source = DBCommentSourceNone;
	}

}

?>