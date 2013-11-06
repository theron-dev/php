<?php

/**
 * 获取用户关注的用户
 * @author hailongz
 *
 */
class ConcernFetchUserTask extends ConcernTask{

	/**
	 *  用户ID　若为 null 则使用内部参数 auth
	 * @var int
	 */
	public $uid;
	
	/**
	 * 允许阻止的用户
	 * @var boolean
	 */
	public $allowBlock;
	
	public $pageIndex;
	public $pageSize;
	
	public function fetchItem($item){
		return true;
	}
}

?>