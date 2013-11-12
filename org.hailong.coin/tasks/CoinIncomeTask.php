<?php

/**
 * 金币
 * @author hailongz
 *
 */
class CoinIncomeTask extends CoinAuthTask{

	/**
	 * 用户ID  为null时使用内部参数 auth
	 * @var int
	 */
	public $uid;
	
	/**
	 * 金币 小于0时为支出， 大于0时为收入
	 * @var double
	 */
	public $coin;

	/**
	* 来源
	* @var String
	*/
	public $source;
	/**
	 * 来源ID
	 * @var int
	 */
	public $sid;
	/**
	 * 来源类型
	 * @var int
	 */
	public $stype;
}

?>