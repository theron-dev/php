<?php

/**
 * Taobao登陆任务
 * @author zhanghailong
 *
 */
class TaobaoLoginTask implements ITask{
	
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
	/**
	 * taobao uid
	 * @var String
	 */
	public $taobao_uid;
	/**
	 * 昵称
	 * @var String
	 */
	public $nick;
	
	public function prefix(){
		return "auth";
	}
	
}

?>