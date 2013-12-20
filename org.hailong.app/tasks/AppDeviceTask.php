<?php

/**
* 应用设置任务
* @author zhanghailong
*
*/

class AppDeviceTask implements ITask{
	
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
	/**
	 * Token
	 * @var String
	 */
	public $token;
	/**
	 * 应用版本
	 * @var String
	 */
	public $version;
	/**
	 * 编译版本
	 * @var String
	 */
	public $build;
	/**
	 * Setting
	 * @var any
	 */
	public $setting;
	/**
	 * 最后修改时间
	 * @var int
	 */
	public $updateTime;

	public function prefix(){
		return "device";
	}
}

?>