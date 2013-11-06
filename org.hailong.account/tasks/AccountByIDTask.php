<?php

/**
 * 获取账户任务
 * @author zhanghailong
 *
 */
class AccountByIDTask implements ITask{
	
	/**
	 * 输出 账号
	 * @var String
	 */
	public $account;
	/**
	 * 输出 标题
	 * @var String
	 */
	public $title;
	/**
	 * 输出 头像
	 */
	public $logo;
	/**
	* 输出 手机号
	*/
	public $tel;
	/**
	* 输出 邮箱
	*/
	public $email;
	/**
	 * 用户ID
	 * @var int
	 */
	public $uid;
	
	public function prefix(){
		return "acc";
	}
}

?>