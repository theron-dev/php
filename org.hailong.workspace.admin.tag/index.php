<?php
$library = "..";

require_once "$library/org.hailong.service/service.php";
require_once "$library/org.hailong.ui/ui.php";
require_once "$library/org.hailong.account/account.php";

require_once "configs/config.php";

require_once "tag.php";

session_start();
	

Shell::staticRun(config(), new SessionViewStateAdapter("workspace/admin/tag/index"),"views/tag.html", "TagSearchController");


?>