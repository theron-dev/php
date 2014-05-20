<?php

class CookieViewStateAdapter implements IViewStateAdapter{
	
	private $alias;
	
	public function __construct($alias = ""){
		$this->alias = $alias;
	}
	
	public function saveViewState($data){
		$vs = isset($_COOKIE["VS"]) ? unserialize( gzuncompress(base64_decode($_COOKIE["VS"]))): array();
		$vs[$this->alias] = $data;
		setcookie("VS", base64_encode(gzcompress( serialize($vs) ,9) ),null);
	}
	
	public function loadViewState(){
		$vs = isset($_COOKIE["VS"]) ? unserialize( gzuncompress(base64_decode($_COOKIE["VS"]))): array();
		return isset($vs[$this->alias]) ? $vs[$this->alias]: null;
	}
	
	public function setContext($context){
		
	}
}

?>