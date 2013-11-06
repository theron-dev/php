<?php

global $library;

if(!$library){
	$library = "..";
}

require_once "$library/org.hailong.service/service.php";
require_once "$library/org.hailong.uri/uri.php";

$dbConfig = require "$library/org.hailong.configs/db_default.php";

defaultDBAdapter($dbConfig["type"],$dbConfig["servername"],$dbConfig["database"],$dbConfig["username"],$dbConfig["password"]);

getDefaultDBAdapter()->setCharset($dbConfig["charset"]);

defaultDBContext(new DBContext());

date_default_timezone_set("PRC");

$context = new ServiceContext(null,array(
	"services" => array(
		array(
			"class" => "URIService",
			"tasks" => array(
				"URIAssignTask"
			)
		)
	)
));

$uri = false;

if(isset($_GET["uri"])){
	$uri = $_GET["uri"];
}
else{
	foreach($_GET as $key=>$value){
		if($value ==""){
			$uri = $key;
			break;
		}
	}
}

$task = new URIAssignTask();
$task->uri = $uri;

$context->handle("URIAssignTask",$task);

if($task->url){
	header("Location: {$task->url}");
	exit;
}
else{
	header("HTTP/1.0 404 Not Found");
	exit;
}

?>