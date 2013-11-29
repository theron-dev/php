<?php

/**
 * 消息发送任务
 * @author zhanghailong
 *
 */
class MessageSendTask extends MessageTask{

	/**
	 * 用户ID,　若不存在,　则使用内部参数 auth， 若为 -1 则是系统发出的信息
	 * @var int
	 */
	public $uid;
	/**
	 * 消息类型
	 * @var MessageType
	 */
	public $mtype;
	/**
	 * 标题
	 * @var String
	 */
	public $title;
	/**
	 * 消息内容
	 * @var String
	 */
	public $body;
	/**
	 * 附件
	 * @var [{"key":"logo","uri":"dfd.jpg","type":"image/jpeg"},...]
	 */
	public $attachs;
	/**
	 * 目标
	 * @var [{"tid":123},{"muid":123},{"uid":123},{"key":123,type:MessageUserType},{"nick":""}]
	 */
	public $targets;
	
	/**
	 * 输出 
	 * @var DBMessage;
	 */
	public $results;
	
	public function __construct(){
		$this->mtype = MessageTypeRich;
	}
}

?>