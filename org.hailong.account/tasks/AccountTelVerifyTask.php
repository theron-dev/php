<?php

/**
 * tel 帐号验证码任务
 * @author zhanghailong
 *
 */
class AccountTelVerifyTask implements ITask{
	
	/**
	 * tel
	 * @var String
	 */
	public $tel;
	
	/**
	 * 验证码 输出
	 * @var String
	 */
	public $verify;
	
	public function prefix(){
		return "auth";
	}
}

?>