<?php

/**
 * email发送任务
 * @author zhanghailong
 *
 */
class eMailSendTask implements ITask{

	/**
	 * 发送者
	 * @var String
	 */
	public $from;
	
	/**
	 * 接收者
	 * @var String
	 */
	public $to;
	/**
	 * 标题
	 * @var String
	 */
	public $title;
	/**
	 * 内容
	 * @var String
	 */
	public $body;
	/**
	* 内容类型
	* @var String
	*/
	public $contentType;
	/**
	 * 附件
	 * @var [{"key":"logo","uri":"dfd.jpg","type":"image/jpeg"}
	 */
	public $attachs;

	public function __construct(){
		$this->from = "";
		$this->contentType = "text/html; charset=UTF-8";
	}
	
	public function prefix(){
		return "email";
	}
}

?>