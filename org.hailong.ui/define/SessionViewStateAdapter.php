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
			
			$data = base64_encode(gzcompress( serialize($this->viewState) ,9) );
		
			file_put_contents($UI_SESSION_DIR.'/'.$sessionId.'_'.md5($this->alias).'.vst', $data);
		}
	}
	
	public function saveViewState($data){
		$this->viewState = $data;
	}
	
	public function loadViewState(){
		
		if($this->viewState === false){
			
			global $UI_SESSION_DIR;
			
			$sessionId = session_id();
			
			$path = $UI_SESSION_DIR.'/'.$sessionId.'_'.md5($this->alias).'.vst';
			
			if(file_exists($path) && ($data = file_get_contents($path))){
				$this->viewState = unserialize( gzuncompress(base64_decode($data)));
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