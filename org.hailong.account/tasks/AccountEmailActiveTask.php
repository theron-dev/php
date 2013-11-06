<?php

/**
 * email 帐号激活任务
 * @author zhanghailong
 *
 */
class AccountEmailActiveTask extends AuthTask{
	
	/**
	 * verify
	 * @var String
	 */
	public $verify;
	
	public function prefix(){
		return "auth";
	}
}

?>