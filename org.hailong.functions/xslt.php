<?php

/**
 * 
 * @param String $xml xml 数据源
 * @param String $xslt XSLT 文件
 */
function xslt_parse($xml,$xslt,$config = null,$functionKeys=null){
	
	$doc = new DOMDocument();
	
	@$doc->loadXML($xml);
	
	global $XSLTConfig;
	
	if($config === null && $XSLTConfig){
		$config = $XSLTConfig;
	}
	
	if($config && $doc->documentElement){
		$fragment = $doc->createDocumentFragment();
		$fragment->appendXML(xml_object_encode($config,"config"));
		$doc->documentElement->appendChild($fragment->firstChild);
	}
	
	$proc = new XSLTProcessor();
	
	if($functionKeys){
		$proc->registerPHPFunctions($functionKeys);
	}
	
	$xsl = new DOMDocument();
	
	$xsl->documentURI = $xslt;
	
	@$xsl->load($xslt, LIBXML_NOCDATA);

	$proc->importStylesheet( $xsl );
	
	return $proc->transformToXml($doc);
}

?>