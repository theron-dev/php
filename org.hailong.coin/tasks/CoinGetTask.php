<?php

/**
 * 获取用户金币
 * @author hailongz
 *
 */
class CoinGetTask extends CoinTask{

	/**
	 * 用户ID  为null时使用内部参数 auth
	 * @var int
	 */
	public $uid;
	
	/**
	 * 金币
	 * @var double
	 */
	public $coin;

}

?>