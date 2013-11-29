<?php

/**
 * 消息 帐号授权任务
 * @author zhanghailong
 *
 */
class MessageAccountAuthorizeTask extends MessageTask{

	/**
	 * 用户ID,　若不存在,　则使用内部参数 auth
	 * @var int
	 */
	public $uid;
	/**
	 * 消息外部用户KEY
	 * @var MessageType
	 */
	public $key;
	/**
	* 消息外部用户类型
	* @var MessageUserType
	*/
	public $type;

	
	public function __construct(){
		
	}
}

?>