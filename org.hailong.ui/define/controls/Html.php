<?php

class Html extends View{
	
	public function setText($text){
		$this->pushAttribute("text",$text);
	}
	
}

?>