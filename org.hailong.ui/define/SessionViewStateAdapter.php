<?php

class SessionViewStateAdapter implements IViewStateAdapter{
	
	private $alias;
	
	public function __construct($alias = ""){
		$this->alias = $alias;
	}
	
	public function saveViewState($data){
		$_SESSION["view/state/".$this->alias] = $data;
	}
	
	public function loadViewState(){
		return isset($_SESSION["view/state/".$this->alias]) ? $_SESSION["view/state/".$this->alias] : null;
	}
	
	public function setContext($context){
		
	}
}

?>