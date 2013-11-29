<?php

/**
 * 消息状态任务
 * @author zhanghailong
 *
 */
class MessageStateTask extends MessageTask{

	/**
	 * 消息ID
	 * @var int
	 */
	public $mid;

	/**
	 * 状态
	 * @var MessageState
	 */
	public $mstate;
}

?>