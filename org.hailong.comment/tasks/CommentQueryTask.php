<?php

/**
* 评论查询任务
* @author zhanghailong
*
*/
class CommentQueryTask extends CommentTask{
	
	/**
	 * 用户ID 发起者, null 不限制
	 * @var int
	 */
	public $uid;
	/**
	 * 目标用户ID, null 不限制
	 * @var int
	 */
	public $tuid;
	/**
	 * 实体类型 , null 不限制
	 * @var int
	 */
	public $etype;
	/**
	 * 实体ID , null 不限制
	 * @var int
	 */
	public $eid;
	
	/**
	 * 目标类型 ,null 不限制
	 * @var int
	 */
	public $ttype;
	/**
	 * 目标ID ,null 不限制
	 * @var int
	 */
	public $tid;
	
	/**
	 * asc,desc 默认 desc
	 * @var String
	 */
	public $orderType;
	
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
	}
}

?>