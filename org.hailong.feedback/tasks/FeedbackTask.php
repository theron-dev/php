<?php

/**
 * 反馈
 * @author hailongz
 *
 */
class FeedbackTask implements ITask{

	/**
	 * 应用标示
	 * @var String
	 */
	public $identifier;
	/**
	 * 应用版本
	 * @var String
	 */
	public $version;
	/**
	 * 应用编译版本
	 * @var String
	 */
	public $build;
	/**
	 * 系统名
	 * @var String
	 */
	public $systemName;
	/**
	 * 系统版本
	 * @var String
	 */
	public $systemVersion;
	/**
	 * 硬件
	 * @var String
	 */
	public $model;
	/**
	 * 设备名称
	 * @var String
	 */
	public $deviceName;
	/**
	 * 设备标示
	 * @var String
	 */
	public $deviceIdentifier;
	/**
	 * 内容
	 * @var String
	 */
	public $body;
	
	public function prefix(){
		return null;
	}
	
}

?>