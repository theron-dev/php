<?php
$library = "..";

require_once "$library/org.hailong.service/service.php";
require_once "$library/org.hailong.ui/ui.php";
require_once "$library/org.hailong.account/account.php";

require_once "controllers/AccountRegisterController.php";
require_once 'configs/config.php';

session_start();

Shell::staticRun(config(), new CookieViewStateAdapter("account/register"),"views/register.html", "AccountRegisterController");

?>