<?php

/**
 * 应用用户 推送
 * @author zhanghailong
 *
 */
class AppUserPushTask implements ITask{
	
	/**
	* 应用ID， 为null时， 使用Service Config 数据 appid
	* @var int
	*/
	public $appid;
	/**
	 * 用户ID, 为null时， 使用内部参数 auth
	 * @var int
	 */
	public $uid;

	public $alert;
	public $badge;
	public $sound;
	public $data;
	
	/**
	* 凭证配置选项
	* @var function($setting)
	*/
	public $settingCheckFn;

	public $badgeFn;
	
	public function prefix(){
		return "push";																							
	}
}

?>