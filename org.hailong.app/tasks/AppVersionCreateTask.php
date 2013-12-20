<?php

/**
* 应用版本创建任务
* @author zhanghailong
*
*/

class AppVersionCreateTask implements ITask{
	
	public $appid;
	public $platform;
	public $version;
	public $updateLevel;
	public $content;
	public $uri;
	
	public function prefix(){
		return "app";
	}
}

?>