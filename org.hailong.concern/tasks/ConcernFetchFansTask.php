<?php

/**
 * 获取用户的粉丝
 * @author hailongz
 *
 */
class ConcernFetchFansTask extends ConcernTask{

	/**
	 *  用户ID　若为 null 则使用内部参数 auth
	 * @var int
	 */
	public $uid;
	
	public $pageIndex;
	public $pageSize;
	
	public function fetchItem($item){
		return true;
	}
}

?>