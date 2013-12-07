<?php

/**
 * 手机绑定任务
 * @author zhanghailong
 *
 */
class AccountTelBindTask extends AuthTask{
	
	/**
	 * 用户ID， 若不存在，则使用内部参数 auth
	 * @var int
	 */
	public $uid;
	
	/**
	 * 手机号
	 * @var String
	 */
	public $tel;
	
	/**
	 * 验证码
	 * @var String
	 */
	public $tel_verify;

}

?>