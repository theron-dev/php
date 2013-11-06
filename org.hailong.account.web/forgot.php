<?php
$library = "..";

require_once "$library/org.hailong.service/service.php";
require_once "$library/org.hailong.ui/ui.php";
require_once "$library/org.hailong.account/account.php";

require_once "controllers/AccountForgotController.php";
require_once 'configs/config.php';

session_start();

Shell::staticRun(config(), new CookieViewStateAdapter("account/forgot"),"views/forgot.html", "AccountForgotController");

?>