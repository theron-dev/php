<?php
$library = "..";

require_once "$library/org.hailong.service/service.php";
require_once "$library/org.hailong.ui/ui.php";
require_once "$library/org.hailong.account/account.php";

require_once "configs/config.php";

require_once "feedback.php";

session_start();

$context = new ServiceContext();

Shell::staticRun(config(), new SessionViewStateAdapter("workspace/admin/feedback/index"),"views/index.html", "FeedbackSearchController");


?>