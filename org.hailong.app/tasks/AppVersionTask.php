<?php

/**
* 应用版本检测任务
* @author zhanghailong
*
*/

class AppVersionTask implements ITask{
	
	public $appid;
	public $platform;
	public $version;

	public function prefix(){
		return "app";
	}
}

?>