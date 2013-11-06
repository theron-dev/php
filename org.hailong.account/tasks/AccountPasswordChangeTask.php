<?php

/**
 * 修改账户密码
 * @author zhanghailong
 *
 */
class AccountPasswordChangeTask extends AuthTask{
	
	/**
	 * 原密码
	 * @var String
	 */
	public $password;
	/**
	 * 新密码
	 * @var String
	 */
	public $newpassword;
	
	public $md5;
	
	public function prefix(){
		return "acc";
	}
}

?>