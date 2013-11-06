<?php

/**
 * 新浪微薄登陆任务
 * @author zhanghailong
 *
 */
class SinaWeiboLoginTask implements ITask{
	
	/**
	 * 外部 APP KEY
	 * @var String
	 */
	public $appKey;
	/**
	*  外部APP SECRET
	*/
	public $appSecret;
	/**
	 * 验证token
	 * @var String
	 */
	public $etoken;
	/**
	* 过期时间
	* @var int
	*/
	public $expires_in;
	
	public function prefix(){
		return "auth";
	}
	
}

?>