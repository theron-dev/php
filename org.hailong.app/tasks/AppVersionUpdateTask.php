<?php

/**
* 应用版本更新任务
* @author zhanghailong
*
*/

class AppVersionUpdateTask implements ITask{
	
	public $avid;
	public $updateLevel;
	public $content;
	public $uri;
	
	public function prefix(){
		return "app";
	}
}

?>