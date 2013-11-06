<?php

/**
 * 账号
 */
if($library){
	
	define("USER_REGISTER_EMAIL_TITLE","辣妈汇: 激活你的帐号");
	define("USER_REGISTER_EMAIL_BODY","你的验证码 : {verify}  <a href='http://account.lamahui.cn/active.php?verify={verify}'>激活</a>");
	
	define("USER_RESET_PWD_EMAIL_TITLE","辣妈汇: 你的帐号密码已经重置");
	define("USER_RESET_PWD_EMAIL_BODY","你的密码 : {password}  <a href='http://account.lamahui.cn/login.php'>登录</a>");
	
	define("USER_AUTO_REGISTER_SMS","欢迎您加入\"辣妈汇\"系统，您的的初始密码是: {password},请访问 http://account.lamahui.org/active.php?tel={tel} 激活您的账号");
	define("USER_AUTO_REGISTER_EMAIL","欢迎您加入\"辣妈汇\"系统，您的的初始密码是: {password},请访问 http://account.lamahui.org/active.php?email={email}  激活您的账号");
	
	define("DB_ACCOUNT","account");
	
	require_once "$library/org.hailong.configs/error_code.php";
	require_once "$library/org.hailong.service/service.php";
	require_once "$library/org.hailong.ui/ui.php";
	
	require_once "$library/org.hailong.account/AccountException.php";
	require_once "$library/org.hailong.account/db/DBAccount.php";
	require_once "$library/org.hailong.account/db/DBAccountInfo.php";
	require_once "$library/org.hailong.account/db/DBAccountBind.php";
	require_once "$library/org.hailong.account/db/DBUserViewState.php";
	require_once "$library/org.hailong.account/db/DBUserRelation.php";
	
	require_once "$library/org.hailong.account/services/AuthService.php";
	require_once "$library/org.hailong.account/services/LoginService.php";
	require_once "$library/org.hailong.account/services/UserViewStateService.php";
	require_once "$library/org.hailong.account/services/UserRelationService.php";
	
	require_once "$library/org.hailong.account/services/AccountBindService.php";
	require_once "$library/org.hailong.account/services/AccountRegisterService.php";
	
	require_once "$library/org.hailong.account/services/AccountService.php";
	
	require_once "$library/org.hailong.account/services/AccountInfoService.php";
	
	require_once "$library/org.hailong.account/services/SinaWeiboLoginService.php";
	
	require_once "$library/org.hailong.account/services/QQLoginService.php";
	
	require_once "$library/org.hailong.account/services/RenrenLoginService.php";
	
	require_once "$library/org.hailong.account/services/TaobaoLoginService.php";
	
	require_once "$library/org.hailong.account/tasks/AuthTask.php";
	require_once "$library/org.hailong.account/tasks/LoginTask.php";
	require_once "$library/org.hailong.account/tasks/LogoutTask.php";
	require_once "$library/org.hailong.account/tasks/UserViewStateLoadTask.php";
	require_once "$library/org.hailong.account/tasks/UserViewStateSaveTask.php";
	require_once "$library/org.hailong.account/tasks/UserViewStateClearTask.php";
	require_once "$library/org.hailong.account/tasks/UserRelationTask.php";
	
	
	require_once "$library/org.hailong.account/tasks/AccountBindTask.php";
	require_once "$library/org.hailong.account/tasks/AccountEmailValidateTask.php";
	require_once "$library/org.hailong.account/tasks/AccountEmailActiveTask.php";
	require_once "$library/org.hailong.account/tasks/AccountEmailRegisterTask.php";
	require_once "$library/org.hailong.account/tasks/AccountEmailResetVerifyTask.php";
	require_once "$library/org.hailong.account/tasks/AccountResetPasswordTask.php";
	require_once "$library/org.hailong.account/tasks/AccountIDTask.php";
	require_once "$library/org.hailong.account/tasks/AccountIDCheckTelTask.php";
	require_once "$library/org.hailong.account/tasks/AccountIDCheckEmailTask.php";
	require_once "$library/org.hailong.account/tasks/AccountIDCheckNickTask.php";
	require_once "$library/org.hailong.account/tasks/AccountByIDTask.php";
	
	require_once "$library/org.hailong.account/tasks/AccountInfoUpdateTask.php";
	require_once "$library/org.hailong.account/tasks/AccountInfoGetTask.php";
	require_once "$library/org.hailong.account/tasks/AccountInfoAddTask.php";
	require_once "$library/org.hailong.account/tasks/AccountInfoPutTask.php";
	
	require_once "$library/org.hailong.account/tasks/AccountPasswordChangeTask.php";
	
	require_once "$library/org.hailong.account/tasks/SinaWeiboLoginTask.php";
	require_once "$library/org.hailong.account/tasks/SinaWeiboBindTask.php";
	
	require_once "$library/org.hailong.account/tasks/QQLoginTask.php";
	require_once "$library/org.hailong.account/tasks/QQBindTask.php";
	
	require_once "$library/org.hailong.account/tasks/RenrenLoginTask.php";
	
	require_once "$library/org.hailong.account/tasks/TaobaoLoginTask.php";
	
	require_once "$library/org.hailong.account/tasks/AccountBindGetTask.php";
	require_once "$library/org.hailong.account/tasks/AccountRegisterTask.php";
	
	require_once "$library/org.hailong.account/controllers/LoginController.php";
	require_once "$library/org.hailong.account/controllers/LogoutController.php";
	require_once "$library/org.hailong.account/controllers/UserInfoController.php";
	
	require_once "$library/org.hailong.account/UserViewStateAdapter.php";
}
?>