<?php

$library = "..";

require_once "$library/org.hailong.service.async/async.php";

if(strtoupper(substr(PHP_OS, 0, 3)) === 'WIN'){

	echo "";
}
else{
	echo "php {$sync_php} >/tmp/null &";
}
?>