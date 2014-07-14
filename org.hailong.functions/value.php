<?php

function ValueForKeys($object,$keys,$defaultValue=null){
	
	if(!is_array($keys)){
		$keys = preg_split("/\\./i", $keys);
	}
	
	$c = count($keys);
	$index = 0;
	$v = $object;
	
	while($index < $c){
		
		$v = Value($v,$keys[$index],null);
		
		if($v === null){
			break;
		}
		
		$index ++;
	}
	
	if($v === null){
		return $defaultValue;
	}
	
	return $v;
}

function Value($object,$key,$defaultValue=null){
	
	if(is_object($object) && isset($object->$key)){
		return $object->$key;
	}
	
	if(is_array($object) && isset($object[$key])){
		return $object[$key];
	}
	
	return $defaultValue;
}

function StringValueForKeys($object,$keys,$defaultValue=''){
	$v = ValueForKeys($object,$keys,null);
	if($v=== null){
		return $defaultValue;
	}
	return ''.$v;
}

function StringValue($object,$key,$defaultValue=''){
	$v = Value($object,$key,null);
	if($v=== null){
		return $defaultValue;
	}
	return ''.$v;
}

function IntValueForKeys($object,$keys,$defaultValue=0){
	$v = ValueForKeys($object,$keys,null);
	if($v=== null){
		return $defaultValue;
	}
	return intval($v);
}

function IntValue($object,$key,$defaultValue=0){
	$v = Value($object,$key,null);
	if($v=== null){
		return $defaultValue;
	}
	return intval($v);
}

function DoubleValueForKeys($object,$keys,$defaultValue=0){
	$v = ValueForKeys($object,$keys,null);
	if($v=== null){
		return $defaultValue;
	}
	return doubleval($v);
}

function DoubleValue($object,$key,$defaultValue=0){
	$v = Value($object,$key,null);
	if($v=== null){
		return $defaultValue;
	}
	return doubleval($v);
}

