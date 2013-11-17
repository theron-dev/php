<?php

/**
* 遍历积分记录任务
* @author zhanghailong
*
*/
class IntegralFetchTask extends IntegralTask{
	
	/**
	 * 用户ID 为null使用内部参数 auth
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