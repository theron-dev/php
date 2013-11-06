<?php

require_once "config.php";

$size = isset($_GET["size"]) ? intval($_GET["size"]) : 0;
$file = isset($_GET["img"]) ? $_GET["img"] : null;

if($file){
	
	$size = getThumbSize($size);
	
	$thumbDir = dirname(__FILE__)."/thumb/".$size."/";
	
	$thumbFile = $thumbDir.$file;
	
	$thumbDir = dirname($thumbFile);
	
	mkdirs($thumbDir);
	
	$file  = dirname(__FILE__)."/".$file;
	
	$urlArray =  getimagesize($file);

	if ($size < $urlArray[0]) {
		if(!file_exists($thumbFile)){
	    	$Iheight= ceil($urlArray[1]/($urlArray[0]/$size));
	    	if(!makethumb($file,$thumbFile,$size,$Iheight)){
	    		$thumbFile = $file;
	    	}
		}
	}else{
		$thumbFile = $file;
	}
	
	if(!file_exists($thumbFile)){
		header("Content-Type: text/html;charset=utf8");
		echo "not found ".$thumbFile;
		exit;
	}
	
	$ctime = filectime($thumbFile);
	$length = filesize($thumbFile);


	if(!isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) || strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) != $ctime){

		$mime = $urlArray['mime'];
		
		if($mime == "image/x-ms-bmp"){
			$mime = "image/jpg";
		}

		header("Content-Type: ".$mime);
		header("Content-Length: ".$length);
		
		header('Last-Modified: '.gmdate('D, d M Y H:i:s \G\M\T', $ctime));
		header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time() + 3600));
		header('Cache-Control: max-age=2592000');
		
		echo fread(fopen($thumbFile,"rb"),$length);
		exit();
	}
	else{
		header("HTTP/1.1 304 Not Modified");
		exit();
	}
}


?>