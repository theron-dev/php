<?php

class SessionViewStateAdapter implements IViewStateAdapter{
	
	private $alias;
	private $data;
	
	public function __construct($alias = ""){
		$this->alias = $alias;
		
		global $UI_SESSION_DIR;
		
		$key = md5($this->alias).'.svs';
		
		$file = $UI_SESSION_DIR.'/'.session_id().'/';
		
		$file .= $key;
		
		if(file_exists($file)){
			$data = file_get_contents($file);
			$this->data = unserialize( gzuncompress($data));
		}
		else{
			$this->data = array();
		}
	}
	
	public function __destruct(){
		
		global $UI_SESSION_DIR;
		
		$key = md5($this->alias).'.svs';
		
		$file = $UI_SESSION_DIR.'/'.session_id().'/';
		
		if(!file_exists($file)){
			mkdir($file,0777,true);
			chmod($file, 0777);
		}
		
		$file .= $key;
		
		$data = gzcompress( serialize($this->data));
		
		file_put_contents($file, $data);
		
	}
	
	public function saveViewState($data){
		$this->data = $data;
	}
	
	public function loadViewState(){
		return $this->data;
	}
	
	public function setContext($context){
		
	}
}

?>