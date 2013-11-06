<?php

class Button extends View{
	
	
	public function getTitle(){
		return $this->getAttribute("title");
	}
	
	public function setTitle($title){
		$this->setAttribute("title",$title);
	}
	
	public function setDisabled($disabled){
		$this->setAttribute("disabled", $disabled);
	}
	
	public function setClickAction($action){
		$this->setAttribute("click-action",$action ? $action->getData() : null);
	}
}

?>