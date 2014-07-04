<?php

class UnionTask implements ITask{
	
	public $tasks;
	
	public function prefix(){
		return "union";
	}
	
}

?>