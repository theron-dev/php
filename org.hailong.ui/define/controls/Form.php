<?php

class Form extends View{
	
	public function setSubmitAction($action){
		$this->setAttribute("submit-action",$action ? $action->getData() : null);
	}
	
	public function getFields(){
		return $this->getAttribute("fields");
	}
	
	public function setFields($fields){
		$this->setAttribute("fields", $fields);
	}
}

?>