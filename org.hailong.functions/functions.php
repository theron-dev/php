<?php

define("MCRYPT_KEY","MIIFejCCBGKgAwIBAgIIcLDhN8y5d9EwDQYJKoZIhvcNAQEFBQAwgZYxCzAJBgNVBAYTAlVTMRMwEQYDVQQKDApBcHBsZSBJbmMuMSwwKgYDVQQLDCNBcHBsZSBXb3JsZHdpZGUgRGV2ZWxvcGVyIFJlbGF0aW9uczFEMEIGA1UEAww7QXBwb");

global $library;

if($library){
	require_once "$library/org.hailong.functions/param.php";
	require_once "$library/org.hailong.functions/plist.php";
	require_once "$library/org.hailong.functions/input.php";
	require_once "$library/org.hailong.functions/output.php";
	require_once "$library/org.hailong.functions/upload.php";
	require_once "$library/org.hailong.functions/inputData.php";
	require_once "$library/org.hailong.functions/client.php";
	require_once "$library/org.hailong.functions/xml.php";
	require_once "$library/org.hailong.functions/xslt.php";
	require_once "$library/org.hailong.functions/resource.php";
	require_once "$library/org.hailong.functions/value.php";
}
?>