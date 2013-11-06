<?php

class UserViewStateAdapter implements IViewStateAdapter{
	
	private $context;
	private $alias;
	
	public function __construct($alias = ""){
		$this->alias = $alias;
	}
	
	public function setContext($context){
		$this->context = $context;	
	}
	
	public function saveViewState($data){
		$task = new UserViewStateSaveTask();
		$task->alias = $this->alias;
		$task->data = json_encode($data);
		
		if(isset($_SESSION["view-state-session"])){
			$task->session = $_SESSION["view-state-session"];
		}
		else{
			$task->session = session_id();
		}
		
		try{
			$this->context->handle("UserViewStateSaveTask",$task);
		}
		catch(Exception $ex){
			
		}
	}
	
	public function loadViewState(){
		$task = new UserViewStateLoadTask();
		$task->alias = $this->alias;

		if(isset($_SESSION["view-state-session"])){
			$task->session = $_SESSION["view-state-session"];
		}
		else{
			$task->session = session_id();
		}
		
		try{
			$this->context->handle("UserViewStateLoadTask",$task);
			if($task->data){
				return json_decode($task->data,true);
			}
		}
		catch(Exception $ex){}
		return null;
	}
}
?>