<?php
function getParam($name,$defaultValue,$inputData=null){
	$value = null;
	if($inputData){
		$value = isset($inputData[$name]) ? $inputData[$name] : $defaultValue;
	}
	else if(array_key_exists($name, $_GET)){
		$value = $_GET[$name];
	}
	else if(array_key_exists($name, $_POST)){
		$value = $_POST[$name];
	}
	return $value===null ? $defaultValue:$value;
}
?>