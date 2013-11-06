<?php

/**
 * 帐号绑定获取任务
 * @author zhanghailong
 *
 */
class AccountBindGetTask extends AuthTask{
	
	/**
	 * 用户ID， 若不存在，则使用内部参数 auth
	 * @var int
	 */
	public $uid;
	/**
	* 类型
	* @var AccountBindType
	*/
	public $type;
	/**
	 * 外部 APP KEY
	 * @var String
	 */
	public $appKey;
	/**
	*  输出 外部APP SECRET
	*/
	public $appSecret;
	/**
	*  输出 外部用户ID
	* @var String
	*/
	public $eid;
	/**
	 * 输出 验证token
	 * @var String
	 */
	public $etoken;
	/**
	 * 输出 过期时间
	 * @var int
	 */
	public $expires_in;

	
	public function prefix(){
		return "auth-bind";
	}
}

?>