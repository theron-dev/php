<?php

class Label extends View{
	
	
	public function getText(){
		return $this->getAttribute("text");
	}
	
	public function setText($text){
		$this->setAttribute("text",$text);
	}
	
}

?>