<?php
$library = "..";

require_once "$library/org.hailong.service/service.php";
require_once "$library/org.hailong.ui/ui.php";
require_once "$library/org.hailong.account/account.php";

$workspace = require("$library/org.hailong.workspace/configs/default.php");

require_once "controllers/DefaultController.php";


session_start();

Shell::staticRun($workspace["config"], new SessionViewStateAdapter("workspace/default"),"views/default.html", "DefaultController");

?>