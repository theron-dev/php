<?php

/**
 * email 帐号验证任务
 * @author zhanghailong
 *
 */
class AccountEmailValidateTask implements ITask{
	
	/**
	 * email
	 * @var String
	 */
	public $email;
	
	public function prefix(){
		return "auth";
	}
}

?>