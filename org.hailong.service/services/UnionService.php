<?php

class UnionService extends Service{
	
	
	public function handle($taskType,$task){
	
		if($task instanceof UnionTask){
			
			$context = $this->getContext();
			
			$tasks = split(",", $task->tasks);
			
			if($tasks){
				
				foreach ($tasks as $item){
					
					$ts = split(":", $item);
					$c = count($ts);
					
					$ttype = null;
					$tclass = null;
					$tns = null;
					
					if($c == 1){
						$tclass = $ttype = $ts[0];
					}
					else if($c == 2){
						$ttype = $ts[0];
						$tclass = $ts[1];
					}
					else if($c == 3){
						$tns = $ts[0];
						$ttype = $ts[1];
						$tclass = $ts[2];
					}
					
					if(class_exists($tclass)){
						
						$t = new $tclass();
						
						$context->fillTask($task,null,$tns);
						
						$context->handle($ttype, $t);
						
					}
					
				}
				
			}
			
		}
		
		return true;
	}
	
}

?>