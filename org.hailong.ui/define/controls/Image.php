<?php

class Image extends View{
	
	public function getSrc(){
		return $this->getAttribute("src");
	}
	
	public function setSrc($src){
		$this->setAttribute("src",$src);
	}
	
}

?>