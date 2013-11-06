<?php

/**
 * 用户界面状态清除任务
 * @author zhanghailong
 *
 */
class UserViewStateClearTask extends AuthTask{
	
	public $session;
	
	public function prefix(){
		return "user-view-state";
	}
	
	public function __construct(){
		$this->session = false;
	}
}

?>