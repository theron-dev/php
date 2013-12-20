<?php

/**
* 应用版本删除任务
* @author zhanghailong
*
*/

class AppVersionRemoveTask implements ITask{
	
	public $avid;
	
	
	public function prefix(){
		return "app";
	}
}

?>