<?php

/**
 * 验证任务
 * @author zhanghailong
 *
 */
class AuthTask implements ITask{
	
	public $token;
	public function prefix(){
		return "auth";
	}
}

?>