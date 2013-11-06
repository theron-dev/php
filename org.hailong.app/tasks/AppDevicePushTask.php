<?php

/**
 * 应用设备 推送
 * @author zhanghailong
 *
 */
class AppDevicePushTask implements ITask{
	
	/**
	* 应用ID， 为null时， 使用Service Config 数据 appid
	* @var int
	*/
	public $appid;
	/**
	 * 设备ID, 为null时， 使用内部参数 device-did
	 * @var int
	 */
	public $did;

	public $alert;
	public $badge;
	public $sound;
	public $data;
	
	public function prefix(){
		return "push";																							
	}
}

?>