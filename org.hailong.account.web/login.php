<?php
$library = "..";

require_once "$library/org.hailong.service/service.php";
require_once "$library/org.hailong.ui/ui.php";
require_once "$library/org.hailong.account/account.php";

require_once "controllers/AccountLoginController.php";
require_once 'configs/config.php';

session_start();

$view = "views/login.html";

if(isset($_GET["display"])){
	if($_GET["display"] == "mini"){
		$view = "views/login_mini.html";
	}
}

Shell::staticRun(config(), new CookieViewStateAdapter("account/login"),$view, "AccountLoginController");

?>