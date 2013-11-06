<?php 

function inputData(){
	
	global $CFG_DEBUG;
	
	$inputData = array();
	
	if($CFG_DEBUG){
		foreach($_GET as $key=>$value){
			$inputData[$key] = $value;
		}
	}
	
	foreach($_POST as $key=>$value){
		$inputData[$key] = $value;
	}
	
	if(isset($inputData["data-json"])){
		$data = $inputData["data-json"];
		if(is_string($data)){
			$data = json_decode($data,true);
		}

		if(is_array($data)){
			foreach($data as $key=>$value){
				$inputData[$key] = $value;
			}
		}
	}
	
	if(isset($inputData["data-plist"])){
		$data = $inputData["data-plist"];
		if(is_string($data)){
			$data = plist_decode($data);
		}
		
		if(is_array($data)){
			foreach($data as $key=>$value){
				$inputData[$key] = $value;
			}
		}
	}
	
	$data = input();
	
	if($data && is_array($data)){
		foreach($data as $key=>$value){
			$inputData[$key] = $value;
		}
	}
	
	return $inputData;
}

?>