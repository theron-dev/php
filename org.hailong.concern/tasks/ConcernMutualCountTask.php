<?php
/**
 * 互相关注数
 * @author zhanghailong
 *
 */
class ConcernMutualCountTask extends ConcernTask{

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