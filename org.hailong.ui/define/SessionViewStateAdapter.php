<?php

class SessionViewStateAdapter implements IViewStateAdapter{
	
	private $alias;
	
	private $viewState;
	
	public function __construct($alias = ""){
		$this->alias = $alias;
		$this->viewState = false;
	}
	
	public function __destruct(){
		
		if($this->viewState){
			global $UI_SESSION_DIR;
			$sessionId = session_id();
			file_put_contents($UI_SESSION_DIR.'/'.$sessionId.'_'.md5($this->alias).'.json', json_encode($this->viewStates));
		}
	}
	
	public function saveViewState($data){
		$this->viewState = $data;
	}
	
	public function loadViewState(){
		
		if($this->viewState === false){
			
			global $UI_SESSION_DIR;
			
			$sessionId = session_id();
			
			$text = file_get_contents($UI_SESSION_DIR.'/'.$sessionId.'_'.md5($this->alias).'.json');
			
			if($text){
				$this->viewState = json_decode($text,true);
			}
			else{
				$this->viewState = null;
			}
		}
		
		return $this->viewState;
	}
	
	public function setContext($context){
		
	}
}

?>