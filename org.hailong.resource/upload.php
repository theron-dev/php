<?php

require_once "config.php";

$rs = array();

$uploadDir = "res/";

var_dump($_FILES);

if($_FILES){	
	foreach($_FILES as $name=>$file){
		if($file["size"] >0 && $file["tmp_name"]){
			
			$extName = upload_extname($file);
			$isImage = false;
			
			if($extName == ".jpeg" || $extName==".jpg"
				|| $extName==".png" || $extName==".gif"){
				$uploadDir = "images/";
				$isImage = true;
			}
			else if($extName == ".spx" || $extName == ".mp3" || $extName == ".m4a"){
				$uploadDir = "audios/";
			}
			else{
				$uploadDir = "res/";
			}
		
			$dir = $uploadDir.date("Y-m-d")."/";
			
			$value = upload_name($file);
			
			if(!file_exists($dir)){
				mkdirs($dir);
			}
			
			move_uploaded_file($file["tmp_name"], $dir.$value);
			
			$rs[$name] = "res:///".$dir.$value;
			
			if($isImage){
				buildThumbs($dir.$value);
			}
		}
	}
}

output($rs);

?>