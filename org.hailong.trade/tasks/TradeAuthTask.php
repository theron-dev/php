<?php

class TradeAuthTask extends AuthTask{

	/**
	 * 用户ID null 使用内部参数 auth
	 * @var int
	 */
	public $uid;
	
	public function prefix(){
		return "trade";
	}
}

?>