<?php

/**
 * 注销任务
 * @author zhanghailong
 *
 */
class LogoutTask implements ITask{

	public function prefix(){
		return "auth";
	}
	
}

?>