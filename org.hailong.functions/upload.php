<?php

/**
 * 上传文件
 * @param String $url
 * @param array $fiels	array("name"=>"@filePath");
 */
function upload($files=null,$url=null){
	
	if($url === null){
		global $library;
		$url = require("$library/org.hailong.configs/resource_url.php");
		$url .= "/upload.php";
	}
	
	$curl = curl_init($url);
	
	curl_setopt($curl, CURLOPT_HEADER, false);

	if(!$files){
		$files = array();
		foreach($_FILES as $name=>$file){
			if($file["tmp_name"]){
				$files[$name] = "@".$file["tmp_name"].";type=".$file["type"];
			}
		}
	}

	curl_setopt($curl, CURLOPT_POSTFIELDS, $files);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	
	$rs = curl_exec($curl);

	if($rs === false){
		echo curl_error($curl);
	}
	
	curl_close($curl);
	
	return json_decode($rs,true);
	
}

function upload_from_url($fromUrl,$name,$url =null,$referer = null){
		
	$fileName = $fromUrl;
	$index = strpos($fileName, "?");
	if($index !== false){
		$fileName = substr($fileName, 0,strlen($fileName) - $index);
	}
	$index = strrpos($fileName, "/");
	if($index !== false){
		$fileName = substr($fileName, $index +1);
	}
	
	$extName =  "";
	$index = strrpos($fileName, ".");
	if($index !== false){
		$extName = substr($fileName, $index);
	}
	
	$tmpFile = tempnam("", "r-").$extName;
	
	$file = fopen($tmpFile, "wb");
		
	$ch = curl_init($fromUrl);
		
	curl_setopt($ch, CURLOPT_FILE, $file);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

	if($referer){
		curl_setopt($ch, CURLOPT_REFERER, $referer);
	}
	
	curl_exec($ch);

	curl_close($ch);
		
	fclose($file);
	
	$rs = upload(array($name=>"@".$tmpFile),$url);
	
	unlink($tmpFile);
	
	return $rs;
}

function upload_extname($file){
	$type = strtolower($file["type"]);
	$value =  "";
	if($type == "image/jpeg" || $type =="image/jpg"){
		$value .= ".jpg";
	}
	else if($type == "image/png"){
		$value .= ".png";
	}
	else if($type == "image/gif"){
		$value .= ".gif";
	}
	else if($type == "audio/speex"){
		$value .= ".spx";
	}
	else {
		$index = strrpos($file["name"],".");
		if($index !== false){
			$value .= strtolower(substr($file["name"], $index));
		}
		else{
			$index = strpos($type, "/");
			if($index !== false){
				$value .= ".".strtolower(substr($type,$index +1));
			}
		}
	}
	
	return $value;
}

function upload_name($file){
	$extname = upload_extname($file);
	if($extname == ".php"){
		$extname = ".txt";
	}
	else if($extname == ".py"){
		$extname = ".txt";
	}
	else if($extname == ".sh"){
		$extname = ".txt";
	}
	else if($extname == ".exe"){
		$extname = ".bin";
	}
	return md5_file($file["tmp_name"]).$extname;
}

function upload_filename($file){
	$index = strrpos($file, "/");
	if($index !== false){
		return substr($file,$index +1);
	}
	return $file;
}

?>