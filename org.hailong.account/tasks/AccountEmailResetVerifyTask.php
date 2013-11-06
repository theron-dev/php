<?php

/**
 * email 帐号重设验证码任务
 * @author zhanghailong
 *
 */
class AccountEmailResetVerifyTask extends AuthTask{
	
	/**
	* 验证码, 输出参数
	* @var String
	*/
	public $verify;
	
	public function prefix(){
		return "auth";
	}
}

?>