<?php

/**
 * 修改应用
 * @author zhanghailong
 *
 */
class AppUpdateTask implements ITask{
	
	/**
	* 应用ID
	* @var int
	*/
	public $appid;
	/**
	 * 应用标题
	 * @var String
	 */
	public $title;
	/**
	 * 说明
	 * @var String
	 */
	public $description;
	
	
	public function prefix(){
		return "app";
	}
}

?>