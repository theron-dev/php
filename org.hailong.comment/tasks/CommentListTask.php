<?php

/**
* 评论列表任务
* @author zhanghailong
*
*/
class CommentListTask extends CommentTask{
	
	/**
	 * 实体类型
	 * @var DBCommentType
	 */
	public $etype;
	/**
	 * 实体ID
	 * @var int
	 */
	public $eid;
	
	/**
	 * 父级评论ID
	 * @var int
	 */
	public $pcid;
	
	public $pageIndex;
	public $pageSize;
	
	/**
	 * 输出
	 * @var array(DBComment,DBComment,...)
	 */
	public $results;
	
	/**
	 * 输出
	 * @var int
	 */
	public $total;

	public function __construct(){
		$this->pageIndex = 1;
		$this->pageSize = 20;
		$this->pcid = 0;
	}
}

?>