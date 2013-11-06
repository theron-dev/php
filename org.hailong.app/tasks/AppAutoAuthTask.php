<?php

/**
* 应用自动验证任务
* @author zhanghailong
*
*/

class AppAutoAuthTask implements ITask{
	
	public $appid;
	public $did;
	
	public function prefix(){
		return "auth";
	}
}

?>