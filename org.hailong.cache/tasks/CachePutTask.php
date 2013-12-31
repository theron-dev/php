<?php

class CachePutTask extends CacheTask{
	
	/**
	* 标示路径
	* @var String
	*/
	public $path;
	
	/**
	 * 值
	 * @var any
	 */
	public $value;
	
	/**
	 * 超时时间，默认 3600 秒
	 * @var int
	 */
	public $expire;
	
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