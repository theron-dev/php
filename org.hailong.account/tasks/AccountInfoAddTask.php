<?php

/**
 * 添加账户信息任务
 * @author zhanghailong
 *
 */
class AccountInfoAddTask extends AuthTask{
	/**
	 * 用户ID 若为null 则使用内部参数 auth
	 * @var int
	 */
	public $uid;
	/**
	 * 
	 * @var String
	 */
	public $key;
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
	
	/**
	 * 输出 信息ID
	 * @var int
	 */
	public $uiid;
	
	public function prefix(){
		return "acc";
	}
}

?>