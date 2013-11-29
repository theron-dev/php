<?php

/**
 * 消息
 */
if($library){
	
	define("MESSAGE_EMAIL_INVOKE_TITLE_FORMAT","久违工作室");
	define("MESSAGE_EMAIL_INVOKE_FORMAT","你的朋友 \"{ftitle}\"　给你发了条信息。 到这 http://msg.9vteam.com 查看 \n");
	define("MESSAGE_SMS_INVOKE_FORMAT","你的朋友 \"{ftitle}\"　给你发了条信息。到这 http://msg.9vteam.com 查看 \n");
	
	define("MESSAGE_INVOKE_SYS_MSG_FORMAT","你已向好友 {ftitle} 发出邀请。他在接收邀请后收到你发的信息。");
	
	define("DB_MESSAGE","message");
	
	global $MESSAGE_SETTING_CHECK_FN;
	global $MESSAGE_BADGE_FN;
	
	require_once "$library/org.hailong.configs/error_code.php";
	
	require_once "$library/org.hailong.log/log.php";
	require_once "$library/org.hailong.account/account.php";
	
	require_once "$library/org.hailong.message/MessageException.php";
	
	require_once "$library/org.hailong.message/db/DBMessage.php";
	require_once "$library/org.hailong.message/db/DBMessageAttach.php";
	require_once "$library/org.hailong.message/db/DBMeeting.php";
	require_once "$library/org.hailong.message/db/DBMeetingMember.php";
	require_once "$library/org.hailong.message/db/DBMessageUser.php";
	
	require_once "$library/org.hailong.message/services/MessageService.php";
	require_once "$library/org.hailong.message/services/MessageRemindService.php";
	require_once "$library/org.hailong.message/services/MessageSendService.php";
	require_once "$library/org.hailong.message/services/MessageStateService.php";
	require_once "$library/org.hailong.message/services/MessageQueryService.php";
	require_once "$library/org.hailong.message/services/MeetingService.php";
	
	require_once "$library/org.hailong.message/tasks/MessageTask.php";
	require_once "$library/org.hailong.message/tasks/MessageRemindTask.php";
	require_once "$library/org.hailong.message/tasks/MessageSendTask.php";
	require_once "$library/org.hailong.message/tasks/MessageLogTask.php";
	require_once "$library/org.hailong.message/tasks/MessageStateTask.php";
	require_once "$library/org.hailong.message/tasks/MessageUserAccessTask.php";
	require_once "$library/org.hailong.message/tasks/MessageUserInvokeTask.php";
	require_once "$library/org.hailong.message/tasks/MessageAccountAuthorizeTask.php";
	require_once "$library/org.hailong.message/tasks/MessageQueryTask.php";
	require_once "$library/org.hailong.message/tasks/MeetingMemberTask.php";
	require_once "$library/org.hailong.message/tasks/MessageSessionStateTask.php";
	require_once "$library/org.hailong.message/tasks/MessageAttachTask.php";

}
?>