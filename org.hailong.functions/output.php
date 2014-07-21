<?php

function output($data,$inputData=null){
	$format = getParam("format","json",$inputData);

	if($format == "plist"){
		header("Content-Type: text/xml;charset=UTF-8");
		echo '<?xml version="1.0" encoding="UTF-8"?>';
		echo '<!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">';
		echo '<plist version="1.0">';
		echo plist_encode($data);
		echo '</plist>';
	}
	else if($format == "xml"){
		header("Content-Type: text/xml;charset=UTF-8");
		xml_object_ouptut($data);
	}
	else if($format == "mcrypt"){
		header("Content-Type: mcrypt");
		
		$d = json_encode($data);
		
		$pad = 8 - (strlen ( $d ) % 8);
		$d =  $d . str_repeat ( chr ( $pad ), $pad );
		
		$key = substr(MCRYPT_KEY, 0, 24);
		
		$d =  mcrypt_encrypt(MCRYPT_3DES, $key, $d, MCRYPT_MODE_ECB);

		echo $d;
	}
	else if($format == "xslt"){
		
		global $library;
		global $XSLTLibrary;
		global $XSLTFunctionKeys;
		
		$xslt = isset($inputData["xslt"]) ? $inputData["xslt"] : "default.xsl";
		
		$name = $xslt;
		$ext = "";
		$index = strrpos($xslt, ".");
		if($index !== false){
			$name = substr($xslt, 0,$index);
			$ext = substr($xslt,$index);
		}
			
		$xslts = array();
			
		$userAgent = $_SERVER["HTTP_USER_AGENT"];
			
		if(strpos($userAgent, "iPhone") !== false || strpos($userAgent, "iPod") !== false){
			$xslts[] = $name."_iPhone".$ext;
		}
			
		if(strpos($userAgent, "iPad") !== false){
			$xslts[] = $name."_iPad".$ext;
		}
			
		if(strpos($userAgent,"WAP") !== false){
			$xslts[] = $name."_wap".$ext;
		}
		
		$xslts[] = $xslt;
		foreach($xslts as $xsl){
			if($XSLTLibrary){
				$xslt = "$XSLTLibrary/$xsl";
			}
			else{
				$xslt = "$library/org.hailong.xslt/$xsl";
			}
			if(file_exists($xslt)){
				break;
			}
		}
			
		$rs = xslt_parse(xml_object_encode($data, "root"),$xslt,$XSLTFunctionKeys) ;
		
		header("Content-Type: text/html; charset=utf-8");
		if($rs === false){
			echo "<html><head><title>XSLT ERROR</title></head><body></body></html>";
		}
		else{
			echo $rs;
		}
			
	}
	else if($format == "jsonp"){
		$callback = getParam("callback","",$inputData);
		header("Content-Type: text/html;charset=utf8");
		echo "<html><head><meta http-equiv='Content-Type' content='text/html; charset=utf-8'></head><body>";
		echo "<script type='text/javascript'>";
		echo $callback;
		echo "(";
		echo json_encode($data);
		echo ");";
		echo "</script>";
		echo "</body></html>";
	}
	else{
		header("Content-Type: text/javascript;charset=utf8");
		echo json_encode($data);
	}
}

?>