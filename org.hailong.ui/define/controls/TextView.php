<?php

class TextView extends View{
	
	
	public function getText(){
		return $this->getAttribute("text");
	}
	
	public function setText($text){
		$this->setAttribute("text",$text);
	}
	
	public function setChangeAction($action){
		$this->setAttribute("change-action",$action ? $action->getData() : null);
	}
}

?>