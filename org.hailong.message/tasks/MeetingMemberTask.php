<?php

/**
 * 消息发送任务
 * @author zhanghailong
 *
 */
class MeetingMemberTask extends MessageTask{

	/**
	 * 会议ID
	 * @var int
	 */
	public $tid;
	
	/**
	 * 输出 
	 * @var array(DBMeetingMember,...);
	 */
	public $results;
	

}

?>