<?php

/**
 * 消息用户访问任务
 * @author zhanghailong
 *
 */
class MessageUserAccessTask extends MessageTask{

	/**
	 * 用户ID, 输出参数
	 * @var int
	 */
	public $uid;
	/**
	* 消息用户ID, 输出参数
	* @var int
	*/
	public $muid;
	/**
	 * 用户是否存在, 输出参数
	 * @var boolean
	 */
	public $exists;
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
	/**
	 * 用户来源
	 * @var String
	 */
	public $source;
	
	public function __construct(){
		$this->source = "org.hailong.message";
	}
}

?>