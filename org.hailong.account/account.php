<?php

/**
 * 账号
 */
if($library){
	

	define("DB_ACCOUNT","account");
	
	require_once "$library/org.hailong.configs/account_cfg.php";
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
	
	require_once "$library/org.hailong.account/services/DoubanLoginService.php";
	
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
	
	require_once "$library/org.hailong.account/tasks/AccountTelVerifyTask.php";
	require_once "$library/org.hailong.account/tasks/AccountTelRegisterTask.php";
	
	require_once "$library/org.hailong.account/tasks/AccountTelBindTask.php";
	require_once "$library/org.hailong.account/tasks/AccountTelUnBindTask.php";
	
	require_once "$library/org.hailong.account/tasks/AccountIDByBindTask.php";
	
	require_once "$library/org.hailong.account/tasks/DoubanLoginTask.php";
	require_once "$library/org.hailong.account/tasks/DoubanBindTask.php";
	
	
	require_once "$library/org.hailong.account/controllers/LoginController.php";
	require_once "$library/org.hailong.account/controllers/LogoutController.php";
	require_once "$library/org.hailong.account/controllers/UserInfoController.php";
	
	require_once "$library/org.hailong.account/UserViewStateAdapter.php";
}
?>