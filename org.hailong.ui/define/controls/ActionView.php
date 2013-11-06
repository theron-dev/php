<?php

class ActionView extends View{
	
	public function setHtml($html){
		$this->pushAttribute("html",$html);
	}
	
	public function getAction(){
		return $this->getAttribute("action");
	}
	
	public function getActionKey(){
		return $this->getAttribute("actionKey");
	}
	
	public function setClickAction($action){
		$this->setAttribute("click-action",$action ? $action->getData() : null);
	}
}

?>