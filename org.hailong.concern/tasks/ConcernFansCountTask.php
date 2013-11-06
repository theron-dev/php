<?php

/**
 * 粉丝数
 * @author zhanghailong
 *
 */
class ConcernFansCountTask extends ConcernTask{

	/**
	 * 用户ID， 为null时使用内部参数auth
	 * @var int
	 */
	public $uid;
	/**
	 * 输出 
	 * @var int
	 */
	public $results;
}

?>