<?php

if(isset($_GET["url"])){
	
	$url = $_GET["url"];
	
	$curl = curl_init($url);
	
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_HEADER, true);
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
	
	$source = curl_exec($curl);
	
	$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE); 
	$contentType = curl_getinfo($curl, CURLINFO_CONTENT_TYPE);
	
	
	if($source && $httpCode == 200){
	
		$html = new DOMDocument();
		
		@$html->loadHTML("<meta http-equiv='Content-Type' content='{$contentType}'>".$source);
		
		$xslt = new XSLTProcessor();
		$xsl = new DOMDocument();
		@$xsl->load('xsl/taobao_item.xsl', LIBXML_NOCDATA);
		$xslt->importStylesheet( $xsl );

		header("Content-Type: text/xml;charset=utf8");
		
		print $xslt->transformToXML( $html );
	}
	else{
		echo $url . "<br />";
		echo $httpCode;
	}
}
