<?php
$library = "..";

require_once "$library/org.hailong.service/service.php";
require_once "$library/org.hailong.ui/ui.php";
require_once "$library/org.hailong.account/account.php";

require_once "controllers/AccountIndexController.php";
require_once 'configs/config.php';

session_start();

Shell::staticRun(config(), new SessionViewStateAdapter("account/index"),"views/index.html", "AccountIndexController");

?>