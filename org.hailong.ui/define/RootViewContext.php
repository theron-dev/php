<?php

class RootViewContext extends ViewContext{
	
	private $version;
	private $data;
	private $updateData;
	private $pushData;
	private $pushFunctions;
	private $redirect;
	
	public function __construct(){
		parent::__construct();
		$this->version = 0;
		$this->data = array();
		$this->updateData = array();
		$this->pushData =array();
		$this->pushFunctions =array();
	}
	
	public function setAttribute($viewId,$name,$value){
		$view = isset($this->updateData[$viewId]) ? $this->updateData[$viewId] : array();
		$view[$name] = $value;
		$this->updateData[$viewId] = $view;
		
		$view = isset($this->data[$viewId]) ? $this->data[$viewId] : array();
		$view[$name] = $value;
		$this->data[$viewId] = $view;
	}
	
	public function getAttribute($viewId,$name){
		if(isset($this->data[$viewId])){
			$view = $this->data[$viewId];
			if(isset($view[$name])){
				return $view[$name];
			}
		}
		return null;
	}
	
	public function pushAttribute($viewId,$name,$value){
		$view = isset($this->pushData[$viewId]) ? $this->pushData[$viewId] : array();
		$view[$name] = $value;
		$this->pushData[$viewId] = $view;
	}
	
	public function loadFromFile($file){
		$dataStr = file_get_contents($file);
		if($dataStr){
			$data = json_decode($dataStr,true);
			$this->version = $data["version"];
			$this->data = $data["data"];
			$this->updateData = array();
		}
	}
	
	public function writeToFile($file){
		file_put_contents($file, json_encode( array("version"=>$this->version,"data"=>$this->data)));
	}
	
	public function loadFromData($data){
		if($data){
			$this->version = $data["version"];
			$this->data = $data["data"];
			$this->updateData = array();
			$this->pushData = array();
			$this->pushFunctions = array();
		}
	}
	
	public function getData(){
		return array("version"=>$this->version,"data"=>$this->data);
	}
	
	public function updateSync($data){
		$version = $data["version"];
		if($this->version == $version){
			if(isset($data["data"])){
				$d = $data["data"];
				foreach($d as $viewId => $view){
					$v = isset($this->data[$viewId]) ? $this->data[$viewId] : array();
					foreach($view as $name=>$value){
						$v[$name] = $value;
					}
					$this->data[$viewId] = $v;
				}
			}
			$this->version ++;
		}
		else{
			throw new ViewException("view version invalid", ERROR_VIEW_VERSION_INVALID);
		}
	}
	
	public function outputSyncData(){
		$rs = array();
		if(count($this->updateData)>0){
			$rs["version"] = $this->version +1;
			$d = array();
			foreach($this->updateData as $key => $view){
				$v = array();
				foreach($view as $name=>$value){
					$v[$name] = $value;
				}
				$d[$key] = $v;
			}
			$rs["data"] = $d;
		}
		else{
			$rs["version"] = $this->version;
		}
		$rs["push-data"] = $this->pushData;
		$rs["push-functions"] = $this->pushFunctions;
		return $rs;
	}
	
	public function outputData(){
		$rs = array();
		$rs["version"] = count($this->updateData)>0 ? $this->version + 1:$this->version;
		$d = array();
		foreach($this->data as $key => $view){
			$v = array();
			foreach($view as $name=>$value){
				$v[$name] = $value;
			}
			$d[$key] = $v;
		}
		$rs["data"] = $d;
		$rs["push-data"] = $this->pushData;
		$rs["push-functions"] = $this->pushFunctions;
		return $rs;
	}
	
	public function finish(){
		if(count($this->updateData)>0){
			$this->version ++;
			$this->updateData = array();
		}
		$this->pushData = array();
		$this->pushFunctions = array();
	}
	
	public function pushFunction($function,$arguments){
		$this->pushFunctions[$function] = $arguments;
	}
	
	public function rootContext(){
		return $this;
	}
	
	public function redirect($url){
		$this->redirect = $url;
	}
	
	public function getRedirect(){
		return $this->redirect;
	}
	
}

?>