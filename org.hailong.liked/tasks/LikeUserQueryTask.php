<?php

/**
* 喜欢的用户查询任务
* @author zhanghailong
*
*/
class LikeUserQueryTask extends LikedAuthTask{
	
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
	* 分页
	* @var int
	*/
	public $pageIndex;
	public $pageSize;
	
	/**
	 * 输出 
	 * @var array(DBLiked,DBLiked,...)
	 */
	public $results;
	
	public function __construct(){
		$this->pageIndex = 1;
		$this->pageSize = 50;
	}
}

?>