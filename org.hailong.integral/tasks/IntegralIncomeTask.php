<?php

/**
* 积分收益任务
* @author zhanghailong
*
*/
class IntegralIncomeTask extends IntegralAuthTask{
	
	/**
	 * 用户ID 为null使用内部参数 auth
	 * @var int
	 */
	public $uid;
	/**
	 * 来源用户ID
	 * @var int
	 */
	public $suid;
	/**
	 * 来源类型
	 * @var int
	 */
	public $stype;
	/**
	 * 来源ID
	 * @var int
	 */
	public $sid;
	/**
	* 值
	* @var double
	*/
	public $value;
	/**
	 * 唯一来源检测
	 * @var boolean
	 */
	public $onlySourceOf;
	
	/**
	 * 输出 
	 * @var DBintegral
	 */
	public $results;
	
	public function __construct(){
		$this->onlySourceOf = true;
	}
}

?>