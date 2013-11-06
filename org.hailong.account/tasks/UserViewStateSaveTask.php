<?php

/**
 * 用户界面状态保存任务
 * @author zhanghailong
 *
 */
class UserViewStateSaveTask extends AuthTask{
	
	public $alias;
	public $data;
	public $session;
	
	public function prefix(){
		return "user-view-state";
	}
	
	public function __construct(){
		$this->session = "";
	}
}

?>