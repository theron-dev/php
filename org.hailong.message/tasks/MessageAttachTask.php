<?php

/**
 * 获取附件任务
 * @author zhanghailong
 *
 */
class MessageAttachTask extends MessageTask{
	
	/**
	 * 消息ID
	 * @var int
	 */
	public $mid;
	
	/**
	 * 输出
	 * @var array(DBMessageAttach,...)
	 */
	public $results;
	
}

?>