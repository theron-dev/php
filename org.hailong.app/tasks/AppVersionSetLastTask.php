<?php

/**
* 应用版本设置最后版本任务
* @author zhanghailong
*
*/

class AppVersionSetLastTask implements ITask{
	
	public $avid;
	
	public function prefix(){
		return "app";
	}
}

?>