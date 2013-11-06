<?php

/**
 * email 帐号重设码任务
 * @author zhanghailong
 *
 */
class AccountResetPasswordTask extends AuthTask{
	
	/**
	 * 账号
	 * @var String
	 */
	public $account;
	
	/**
	* 验证码, 输出参数
	* @var String
	*/
	public $password;
	
	public function prefix(){
		return "auth";
	}
}

?>