<?php

class Toolbar extends View{
	
	
	public function getItems(){
		return $this->getAttribute("items");
	}
	
	public function setItems($items){
		$this->setAttribute("items",$items);
	}

	public function getAction(){
		return $this->getAttribute("action");
	}
	
	public function setClickAction($action){
		$this->setAttribute("click-action",$action ? $action->getData() : null);
	}
}

?>