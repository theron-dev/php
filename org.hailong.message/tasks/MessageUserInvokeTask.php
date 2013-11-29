<?php

/**
 * 消息用户邀请任务
 * @author zhanghailong
 *
 */
class MessageUserInvokeTask extends MessageTask{

	/**
	 * 用户ID, 若为存在，则使用内部参数 auth
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