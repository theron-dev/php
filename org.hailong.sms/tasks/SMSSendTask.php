<?php

/**
 * 短信发送任务
 * @author zhanghailong
 *
 */
class SMSSendTask implements ITask{
	
	/**
	 * 手机号
	 * @var String or Array
	 */
	public $tel;
	/**
	 * 短信内容
	 * @var String
	 */
	public $body;
	
	public function prefix(){
		return "sms";
	}
}
?>