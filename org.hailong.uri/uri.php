<?php
/**
* URI
*/
if($library){
	
	define("DB_URI","uri");
	
	require_once "$library/org.hailong.uri/db/DBURI.php";
	
	require_once "$library/org.hailong.uri/tasks/URITask.php";
	require_once "$library/org.hailong.uri/tasks/URIAssignTask.php";
	
	require_once "$library/org.hailong.uri/services/URIService.php";
}
?>