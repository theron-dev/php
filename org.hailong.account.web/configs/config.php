<?php

date_default_timezone_set("PRC");

define("STATISTICS_ACCOUNT","account");

if($library){
    require_once "$library/org.hailong.account/account.php";
    require_once "$library/org.hailong.log/log.php";
    require_once "$library/org.hailong.app/app.php";
    require_once "$library/org.hailong.email/email.php";
    require_once "$library/org.hailong.service.async/async.php";
    require_once "$library/org.hailong.statistics/statistics.php";
    require_once "$library/org.hailong.renren/renren.php";
    require_once "$library/org.hailong.sina.weibo/weibo.php";
    
    $dbConfig = require("$library/org.hailong.configs/db_default.php");

    defaultDBAdapter($dbConfig["type"],$dbConfig["servername"]
    	,$dbConfig["database"],$dbConfig["username"],$dbConfig["password"]);
    
    getDefaultDBAdapter()->setCharset($dbConfig["charset"]);
    
    defaultDBContext(new DBContext());
}

function config(){
	return array(
		"services" => array(
			array(
				"class" => "DBService",
				"tasks" => array(
					"DBContextTask"
				),
				"config" => array(
					"entitys"=>array("DBAccount","DBAccountBind","DBUserViewState","DBLog","DBAsyncTask","DBStatisticsUniversal","DBCache","DBUserRelation"
					,"DBApp","DBAppAuth","DBAppDevice","DBAppVersion")
				),
				"createInstance" =>true,
			),
			array(
				"class" => "LogService",
				"tasks" => array(
					"LogTask"
				),
			),
			array(
				"class" => "AsyncTaskService",
				"tasks" => array(
					"AsyncActiveTask"
				),
				"config" => array(
					"config"=>"config"
				),
			),
			array(
				"class" => "LoginService",
				"tasks" => array(
					"LoginTask","LogoutTask"
				),
			),
			array(
				"class" => "AuthService",
				"tasks" => array(
					"AuthTask","UserViewStateSaveTask","UserViewStateLoadTask","UserViewStateClearTask","AccountBindTask","AccountEmailActiveTask","AccountEmailResetVerifyTask"
				),
			),
			array(
				"class" => "AppAuthService",
				"tasks" => array(
					"AppAutoAuthTask","AppAuthTask"
				),
			),
			array(
				"class" => "UserViewStateService",
				"tasks" => array(
					"UserViewStateSaveTask","UserViewStateLoadTask","UserViewStateClearTask"
				),
			),
			array(
				"class" => "AccountRegisterService",
				"tasks" => array(
					"AccountEmailActiveTask","AccountEmailRegisterTask","AccountEmailResetVerifyTask","AccountEmailValidateTask","AccountResetPasswordTask"
				),
			),
			array(
				"class" => "AccountBindService",
				"tasks" => array(
					"AccountBindTask"
				),
			),
			array(
				"class" => "StatisticsService",
				"tasks" => array(
					"StatisticsTask"
				),
			),
			array(
				"class" => "CacheService",
				"tasks" => array(
						"CacheGetTask","CachePutTask",
				),
			),
			array(
				"class" => "SinaWeiboLoginService",
				"tasks" => array(
					"SinaWeiboLoginTask","SinaWeiboBindTask"
				),
			),
			array(
				"class" => "QQLoginService",
				"tasks" => array(
					"QQLoginTask","QQBindTask"
				),
			),
			array(
				"class" => "RenrenLoginService",
				"tasks" => array(
					"RenrenLoginTask"
				),
			),
			array(
				"class" => "TaobaoLoginService",
				"tasks" => array(
					"TaobaoLoginTask"
				),
			),
			array(
				"class" => "UserRelationService",
				"tasks" => array(
					"UserRelationTask"
				),
			),
		),
	);
}

?>