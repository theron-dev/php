<?php

/**
 * 写账户信息任务
 * @author zhanghailong
 *
 */
class AccountInfoPutTask extends AuthTask{
	
	/**
	 * 用户ID 若为 null 则使用内部参数 auth
	 * @var int
	 */
	public $uid;
	/**
	 * 信息Key
	 * @var String
	 */
	public $key;
	/**
	 * 短值
	 * @var String
	 */
	public $value;
	/**
	 * 长值
	 * @var String
	 */
	public $text;
	
	
	public function prefix(){
		return "acc";
	}
}

?>