<?php

function xml_object_encode($data,$tag){
	
	$isArray = true;
	
	if(is_array($data)){
	    foreach ($data as $key=>$value){
            if("".$key != "".intval($key)){
                 $isArray = false;
                 break;
            }
        }
	}
	else{
		$isArray = false;
	}
	
	if($isArray && count($data) >0){
		$rs = "<$tag>";
		foreach($data as $value){
			$rs .= xml_object_encode($value,"item");
		}
		return $rs."</$tag>";
	}
	else if(!is_array($data) && !is_object($data)){
		return "<$tag>".$data."</$tag>";
	}
	else{
		$rs = "<$tag";
		
		$childs = array();
		
		foreach ($data as $key=>$value){
			if($value !== null){
				if(is_array($value) || is_object($value)){
					$childs[$key] = $value;
				}
				else{
					$rs .= " $key=\"".htmlspecialchars($value)."\"";
				}
			}
		}
		
		$rs .= ">";
		
		foreach($childs as $key=>$value){
			$rs .= xml_object_encode($value,$key);
		}
		
		$rs .= "</$tag>";
		
		return $rs;
	}
}

?>