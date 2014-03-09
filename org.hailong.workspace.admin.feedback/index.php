<?php
$library = "..";

require_once "$library/org.hailong.service/service.php";
require_once "$library/org.hailong.ui/ui.php";
require_once "$library/org.hailong.account/account.php";

require_once "configs/config.php";

require_once "crash.php";

session_start();

$context = new ServiceContext();

Shell::staticRun(config(), new SessionViewStateAdapter("workspace/admin/crash/index"),"views/index.html", "CrashSearchController");


?>