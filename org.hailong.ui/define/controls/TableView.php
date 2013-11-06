<?php

class TableView extends View{
	
	public function setItems($items){
		$this->pushAttribute("items",$items);
	}
	
	public function getAction(){
		return $this->getAttribute("action");
	}
	
	public function getActionKey(){
		return $this->getAttribute("actionKey");
	}
	
	public function getActionData(){
		return $this->getAttribute("actionData");
	}
	
	public function setClickAction($action){
		$this->setAttribute("click-action",$action ? $action->getData() : null);
	}
}

?>