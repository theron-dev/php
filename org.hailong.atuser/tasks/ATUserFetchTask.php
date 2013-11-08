<?php

/**
 * 获取@用户
 * @author hailongz
 *
 */
class ATUserFetchTask extends ATUserTask{

	/**
	 *  用户ID　若为 null 则使用内部参数 auth
	 * @var int
	 */
	public $uid;
	
	public $pageIndex;
	public $pageSize;
	
	/**
	 * 
	 * @param DBATUser $item
	 */
	public function fetchItem($item){
		return true;
	}
}

?>