<?php

/**
 * 创建应用
 * @author zhanghailong
 *
 */
class AppCreateTask implements ITask{
	
	/**
	* 应用ID, 若为null ，则自动生成
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
	/**
	 * 创建者ID , 若为null 则使用内部参数 auth
	 * @var int
	 */
	public $uid;
	
	public function prefix(){
		return "app";
	}
}

?>