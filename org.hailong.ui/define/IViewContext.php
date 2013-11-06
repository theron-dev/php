<?php

interface IViewContext{
	
	public function setAttribute($viewId,$name,$value);
	
	public function getAttribute($viewId,$name);
	
	public function pushAttribute($viewId,$name,$value);
	
	public function pushFunction($function,$arguments);
	
	public function redirect($url);
	
	public function getValue($key);
	
	public function setValue($key ,$value);
	
	public function baseId();
	
	public function rootContext();
	
	public function getViewId($viewId);
}

class ViewContext implements IViewContext{
	
	private $values;
	private $baseId;
	private $rootContext;
	
	public function __construct($baseId="",$parent=null){
		$this->values = array();
		if($parent){
			if($baseId == ""){
				$this->baseId = $parent->baseId();
			}
			else{
				$this->baseId = $parent->baseId();
				if($this->baseId == ""){
					$this->baseId = $baseId;
				}
				else{
					$this->baseId = $this->baseId."_".$baseId;
				}
			}
			$this->rootContext = $parent->rootContext();
		}
	}
	
	
	public function setAttribute($viewId,$name,$value){
		if($this->rootContext){
			$this->rootContext->setAttribute($viewId,$name,$value);
		}
	}
	
	public function getAttribute($viewId,$name){
		if($this->rootContext){
			return $this->rootContext->getAttribute($viewId,$name);
		}
		return null;
	}
	
	public function pushAttribute($viewId,$name,$value){
		if($this->rootContext){
			$this->rootContext->pushAttribute($viewId,$name,$value);
		}
	}
	
	public function pushFunction($function,$arguments){
		if($this->rootContext){
			$this->rootContext->pushFunction($function,$arguments);
		}
	}
	
	public function redirect($url){
		if($this->rootContext){
			$this->rootContext->redirect($url);
		}
	}
	
	public function getValue($key){
		return isset($this->values[$key]) ? $this->values[$key] : null;
	}
	
	public function setValue($key ,$value){
		$this->values[$key] = $value;
	}
	
	public function baseId(){
		return $this->baseId;
	}
	
	
	public function rootContext(){
		return $this->rootContext;
	}
	
	public function getViewId($viewId){
		if($this->baseId == ""){
			return $viewId;
		}
		return $this->baseId."_".$viewId;
	}
}

$viewContexts = array();

function getCurrentViewContext(){
	global $viewContexts;
	$count = count($viewContexts);
	if($count>0){
		return $viewContexts[$count -1];
	}
	return null;	
}

function pushViewContext($viewContext){
	global $viewContexts;
	return array_push($viewContexts,$viewContext);
}

function popViewContext(){
	global $viewContexts;
	return array_pop($viewContexts);
}



?>