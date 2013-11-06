<?php

class DemoService extends Service{
	
	
	public function handle($taskType,$task){
		$task->title = @"OK";
		$this->getContext()->outputTask($task);
		return false;
	}
	
}

?>