<?php

/**
 * email 帐号注册任务
 * @author zhanghailong
 *
 */
class AccountEmailRegisterTask extends AccountEmailValidateTask{
	
	/**
	 * 用户ID， 输出参数
	 * @var int
	 */
	public $uid;
	/**
	 * 验证码, 输出参数
	 * @var String
	 */
	public $verify;
	/**
	 * 密码
	 * @var String
	 */
	public $password;
	
	public function prefix(){
		return "auth";
	}
}

?>