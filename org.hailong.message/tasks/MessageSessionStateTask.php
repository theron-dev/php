<?php

/**
 * 消息回话状态任务
 * @author zhanghailong
 *
 */
class MessageSessionStateTask extends MessageTask{

	/**
	 * 最大消息ID
	 * @var int
	 */
	public $maxMid;

	/**
	 * 会话用户ID 
	 * @var int
	 */
	public $uid;
	
	/**
	 * 状态
	 * @var MessageState
	 */
	public $mstate;
}

?>