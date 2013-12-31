<?php


global $library;

$dbConfig = require "$library/org.hailong.configs/db_default.php";

defaultDBAdapter($dbConfig["type"],$dbConfig["servername"],$dbConfig["database"],$dbConfig["username"],$dbConfig["password"]);

getDefaultDBAdapter()->setCharset($dbConfig["charset"]);

defaultDBContext(new DBContext());

date_default_timezone_set("PRC");

require_once "$library/org.hailong.authority/authority.php";
require_once "$library/org.hailong.statistics/statistics.php";

define("STATISTICS_WORKSPACE","workspace");

return array(
	"title" => "管理系统",
	"logo" => "",
	"modules" => require("$library/org.hailong.configs/workspace.php"),
	"config" =>array(
		"services" => array(
			array(
				"class" => "DBService",
				"tasks" => array(
					"DBContextTask"
					),
				"config" => array(
					"entitys"=>array("DBAccount","DBUserViewState"
							,"DBAuthority","DBAuthorityRole","DBAuthorityEntity"
							,"DBLog","DBStatisticsUniversal","DBCache")
				),
				"createInstance" =>true,
			),
			array(
				"class" => "LogService",
				"tasks" => array(
					"LogTask"
				),
				"config" => array(
					"filter"=>LogLevelError
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
					"AuthTask","UserViewStateSaveTask","UserViewStateLoadTask","UserViewStateClearTask","StatisticsTask"
				),
			),
			array(
				"class" => "UserViewStateService",
				"tasks" => array(
					"UserViewStateSaveTask","UserViewStateLoadTask","UserViewStateClearTask"
				),
			),
			array(
				"class" => "AuthorityValidateService",
				"tasks" => array(
					"AuthorityEntityValidateTask","AuthorityRoleValidateTask",
				),
			),
			array(
				"class" => "StatisticsService",
				"tasks" => array(
					"StatisticsTask",
				),
			),
			array(
				"class" => "CacheService",
				"tasks" => array(
					"CacheGetTask","CachePutTask",
				),
			),
		),
	),
);

?>