<?php

/**
 * 删除应用
 * @author zhanghailong
 *
 */
class AppRemoveTask implements ITask{
	
	/**
	* 应用ID
	* @var int
	*/
	public $appid;
	
	
	public function prefix(){
		return "app";
	}
}

?>