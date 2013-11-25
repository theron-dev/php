<?php

/**
 * tel 帐号注册任务
 * @author zhanghailong
 *
 */
class AccountTelRegisterTask implements ITask{
	
	/**
	 * 用户ID， 输出参数
	 * @var int
	 */
	public $uid;
	/**
	 * 昵称 检测唯一
	 * @var String
	 */
	public $nick;
	/**
	 * tel
	 * @var String
	 */
	public $tel;
	/**
	 * 验证码
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