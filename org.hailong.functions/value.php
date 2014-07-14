<?php

function Value($object,$key,$defaultValue=null){
	
	if(isset($object->$key)){
		return $object->$key;
	}
	
	if(isset($object[$key])){
		return $object[$key];
	}
	
	return $defaultValue;
}

function StringValue($object,$key,$defaultValue=''){
	$v = Value($object,$key,null);
	if($v=== null){
		return $defaultValue;
	}
	return ''.$v;
}

function IntValue($object,$key,$defaultValue=0){
	$v = Value($object,$key,null);
	if($v=== null){
		return $defaultValue;
	}
	return intval($v);
}

function DoubleValue($object,$key,$defaultValue=0){
	$v = Value($object,$key,null);
	if($v=== null){
		return $defaultValue;
	}
	return doubleval($v);
}

