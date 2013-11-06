<?php

class Action{

	private $data;
	
	public function __construct($controller,$action="Action"){
		$this->data = array("action"=>$action,"target"=>$controller->getTarget());
	}
	
	public function __destruct(){
		$this->data = null;
	}
	
	public function setValue($key,$value){
		$this->data[$key] = $value;
		return $this;
	}
	
	public function setSource($key,$viewId,$name){
		$this->data[$key] = array("vid"=>$viewId,"name"=>$name);
		return $this;
	}
	
	public function getData(){
		return $this->data;
	}
}

?>