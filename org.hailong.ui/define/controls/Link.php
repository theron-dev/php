<?php

class Link extends View{
	
	
	public function getText(){
		return $this->getAttribute("text");
	}
	
	public function setText($text){
		$this->setAttribute("text",$text);
	}
	
	public function getHref(){
		return $this->getAttribute("href");
	}
	
	public function setHref($href){
		$this->setAttribute("href", $href);
	}
}

?>