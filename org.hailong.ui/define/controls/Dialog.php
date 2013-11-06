<?php

class Dialog extends View{

	public function getDialog(){
		return $this->getAttribute("dialog");
	}
	
	public function setDialog($dialog){
		$this->setAttribute("dialog",$dialog);
	}
	
	public function getSource(){
		return $this->getAttribute("source");
	}
	
	public function setSource($source){
		$this->setAttribute("source",$source);
	}
	
	public function getResult(){
		return $this->getAttribute("result");
	}
	
	public function setResult($result){
		$this->setAttribute("result", $result);
	}
	
	public function setArgument($argument){
		$this->setAttribute("argument",$argument);
	}
	
	public function getArgument(){
		return $this->getAttribute("argument");
	}
}

?>