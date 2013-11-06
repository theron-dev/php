<?php

/**
* 应用验证任务
* @author zhanghailong
*
*/

class AppAuthTask implements ITask{
	
	public $appid;
	public $uid;
	public $did;
	public $token;
	public $setting;
	
	public $account;
	public $password;
	public $md5;
	
	public function __construct(){
		$this->md5 = false;
	}
	
	public function prefix(){
		return "auth";
	}
}

?>