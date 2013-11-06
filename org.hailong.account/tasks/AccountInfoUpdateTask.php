<?php

/**
 * 修改账户信息任务
 * @author zhanghailong
 *
 */
class AccountInfoUpdateTask extends AuthTask{
	/**
	 * 用户信息ID
	 * @var int
	 */
	public $uiid;
	/**
	 * 值
	 * @var String
	 */
	public $value;
	/**
	* 大值
	* @var String
	*/
	public $text;
	
	public function prefix(){
		return "acc";
	}
}

?>