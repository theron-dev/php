<?php

/**
* 喜欢查询任务
* @author zhanghailong
*
*/
class LikeQueryTask extends LikedAuthTask{
	
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
	
	/**
	 * 输出　总数
	 * @var int
	 */
	public $total;
	
	public function __construct(){
		$this->pageIndex = 1;
		$this->pageSize = 50;
	}
}

?>