<?php

/**
* 积分总计任务
* @author zhanghailong
*
*/
class IntegralTotalTask extends IntegralTask{
	
	/**
	 * 用户ID 为null使用内部参数 auth
	 * @var int
	 */
	public $uid;
	
	/**
	 * 输出 总计值
	 * @var double
	 */
	public $results;
}

?>