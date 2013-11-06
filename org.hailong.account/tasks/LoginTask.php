<?php

/**
 * 登陆任务
 * @author zhanghailong
 *
 */
class LoginTask implements ITask{
	
	public $account;
	public $password;
	public $md5;
	
	public function prefix(){
		return "auth";
	}
	
	public function __construct(){
		$this->md5 = false;
	}
}

?>