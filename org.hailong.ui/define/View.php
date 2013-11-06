<?php

class View{
	
	private $id;
	private $viewContext;
	
	public function __construct($id,$viewContext=null){
		if($viewContext == null){
			$this->viewContext = getCurrentViewContext();
		}
		else{
			$this->viewContext = $viewContext;
		}
		$this->id = $this->viewContext->getViewId($id);
	}
	
	public function __destruct(){
		$this->id = null;
		$this->viewContext = null;
	}
	
	public function getAttribute($name){
		return $this->viewContext->getAttribute($this->id,$name);
	}
	
	public function setAttribute($name,$value){
		$this->viewContext->setAttribute($this->id,$name,$value);
		return $this;
	}
	
	public function pushAttribute($name,$value){
		$this->viewContext->pushAttribute($this->id,$name,$value);
		return $this;
	}
	
	public function setStyleName($value){
		if($this->getAttribute("styleName") != $value){
			$this->setAttribute("styleName",$value);
		}
	}
	
	public function getStyleName(){
		return $this->getAttribute("styleName");
	}
	
	public function isHidden(){
		return $this->getAttribute("hidden");
	}
	
	public function setHidden($hidden){
		if($this->getAttribute("hidden") !== $hidden){
			$this->setAttribute("hidden",$hidden);
		}
	}
	
	public function getId(){
		return $this->id;
	}
}
?>