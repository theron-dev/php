<?php
$library = "..";

require_once "$library/org.hailong.service/service.php";
require_once "$library/org.hailong.ui/ui.php";
require_once "$library/org.hailong.service.async/async.php";

require_once "configs/config.php";

require_once "asynctask.php";

session_start();
	

Shell::staticRun(config(), new SessionViewStateAdapter("workspace/admin/asynctask/index"),"views/asynctask.html", "AsyncTaskSearchController");


?>