<?php

/**
 * 帐号注册任务
 * @author zhanghailong
 *
 */
class AccountRegisterTask implements ITask{
	
	/**
	 * 用户ID， 输出参数
	 * @var int
	 */
	public $uid;
	/**
	 * 帐号名 检测唯一
	 * @var String
	 */
	public $account;
	/**
	 * 邮箱
	 * @var String
	 */
	public $email;
	/**
	 * 电话
	 * @var String
	 */
	public $tel;
	/**
	 * 密码
	 * @var String
	 */
	public $password;
	/**
	 * 名称
	 * @var String
	 */
	public $title;
	/**
	 * 昵称 检测唯一
	 * @var String
	 */
	public $nick;

	public function prefix(){
		return "acc";
	}
}

?>