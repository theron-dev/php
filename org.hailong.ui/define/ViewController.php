<?php

class ViewController{

	private $context;
	private $isPostback;
	private $target;
	
	public function __construct($context,$isPostback=false){
		$this->context = $context;
		$this->isPostback = $isPostback;
		$this->target = Shell::getCurrent()->length();
		Shell::getCurrent()->addController($this);
		
	}
	
	public function onLoadView(){
		
	}
	
	public function onAction(){
		
	}
	
	
	public function isPostback(){
		return $this->isPostback;
	}
	
	public function getContext(){
		return $this->context;
	}
	
	public function getTarget(){
		return $this->target;
	}
}

?>