<?php

/**
* 应用验证删除任务
* @author zhanghailong
*
*/

class AppAuthRemoveTask implements ITask{
	
	public $appid;
	public $uid;

	public function prefix(){
		return "auth";
	}
}

?>