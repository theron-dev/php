<?php

class CacheGetTask extends CacheTask{
	/**
	 * 标示路径
	 * @var String
	 */
	public $path;
	
	/**
	 * 输出 值
	 * @var any
	 */
	public $value;
	
	/**
	 * 输出 时间戳
	 * @var int
	 */
	public $timestamp;
	/**
	 * 输出 缓存ID
	 * @var int
	 */
	public $cid;
}

?>