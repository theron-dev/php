<?php


global $library;

if($library){

$dbConfig = require "$library/org.hailong.configs/db_default.php";

defaultDBAdapter($dbConfig["type"],$dbConfig["servername"],$dbConfig["database"],$dbConfig["username"],$dbConfig["password"]);

getDefaultDBAdapter()->setCharset($dbConfig["charset"]);

defaultDBContext(new DBContext());

date_default_timezone_set("PRC");


    require_once "$library/org.hailong.authority.admin/admin.php";
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
					"entitys"=>array("DBAccount","DBUserViewState","DBAuthority","DBAuthorityRole","DBAuthorityEntity","DBLog")
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
					"AuthTask","UserViewStateSaveTask","UserViewStateLoadTask","UserViewStateClearTask","AuthorityRoleAddTask","AuthorityRoleRemoveTask","AuthorityRoleUpdateTask"
					,"AuthorityEntityAddTask","AuthorityEntityRemoveTask","AuthorityEntityUpdateTask","AuthorityAddTask","AuthorityRemoveTask","AuthorityEntityValidateTask","AuthorityRoleValidateTask"
				),
			),
			array(
				"class" => "UserViewStateService",
				"tasks" => array(
					"UserViewStateSaveTask","UserViewStateLoadTask","UserViewStateClearTask"
				),
			),			
			array(
				"class" => "AccountInfoService",
				"tasks" => array(
					"AccountInfoGetTask"
				),
			),
			array(
				"class" => "AuthorityValidateService",
				"tasks" => array(
					"AuthorityEntityValidateTask","AuthorityRoleValidateTask","AuthorityRoleAddTask","AuthorityRoleRemoveTask","AuthorityRoleUpdateTask"
					,"AuthorityEntityAddTask","AuthorityEntityRemoveTask","AuthorityEntityUpdateTask","AuthorityAddTask","AuthorityRemoveTask"
				),
			),
			array(
				"class" => "AuthorityRoleAdminService",
				"tasks" => array(
					"AuthorityRoleAddTask","AuthorityRoleRemoveTask","AuthorityRoleUpdateTask"
				),
			),
			array(
				"class" => "AuthorityEntityAdminService",
				"tasks" => array(
					"AuthorityEntityAddTask","AuthorityEntityRemoveTask","AuthorityEntityUpdateTask"
				),
			),
			array(
				"class" => "AuthorityAdminService",
				"tasks" => array(
					"AuthorityAddTask","AuthorityRemoveTask"
				),
			),
		),
	);
}

?>