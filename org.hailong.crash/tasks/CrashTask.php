<?php

/**
 * Crash
 * @author hailongz
 *
 */
class CrashTask implements ITask{

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
	 * 异常对信息对象
	 * @var Object
	 */
	public $exception;
	
	public function prefix(){
		return null;
	}
	
}

?>