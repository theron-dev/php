<?php

class WebView extends View{
	
	public function getUrl(){
		return $this->getAttribute("url");
	}
	
	public function setUrl($url){
		$this->setAttribute("url",$url);
	}
	
}

?>