<?php

class ListView extends View{
	
	public function setItems($items){
		$this->pushAttribute("items",$items);
	}
	
	public function getSelectedValue(){
		return $this->getAttribute("selectedValue");
	}
	
	public function setSelectedValue($value){
		return $this->setAttribute("selectedValue",$value);
	}
	
	public function setSelectedChangeAction($action){
		$this->setAttribute("selected-action",$action ? $action->getData() : null);
	}
}

?>