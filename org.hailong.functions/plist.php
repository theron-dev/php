<?php

function plist_encode($data){
	if(is_bool($data)){
		return $data ? "<true />":"<false />";
	}
	else if(is_string($data)){
		return "<string>".htmlentities($data, ENT_QUOTES | ENT_IGNORE, "UTF-8")."</string>";
	}
	else if(is_int($data) || is_long($data)){
		return "<integer>$data</integer>";
	}
	else if(is_float($data)){
		return "<real>$data</real>";
	}
	else if(is_object($data)){
		$rs = "<dict>";
		foreach ($data as $key=>$value){
			$rs .= "<key>$key</key>".plist_encode($value);
		}
		return $rs."</dict>";
	}
	else if(is_array($data)){
		$isobject = false;
        if(count($data) == 0){
            $isobject = true;
        }
		else{
            foreach ($data as $key=>$value){
                if("".$key != "".intval($key)){
                    $isobject = true;
                    break;
                }
            }
        }
		if($isobject){
			$rs = "<dict>";
			foreach ($data as $key=>$value){
				$rs .= "<key>$key</key>".plist_encode($value);
			}
			return $rs."</dict>";
		}
		else{
			$rs = "<array>";
			foreach ($data as $key=>$value){
				$rs .= plist_encode($value);
			}
			return $rs."</array>";
		}
	}
	return "<string></string>";
}

function plist_elementToTextContext($element){
	$rs = "";
	if($element->nodeName =="#text"){
		return $element->nodeValue;
	}
	else{
		$childs = $element->childNodes;
		for($i=0;$i<$childs->length;$i++){
			$child = $childs->item($i);
			$rs .= plist_elementToTextContext($child);
		}
	}
	return $rs;
}

function plist_elementToObject($element){
	if($element){
		if($element->nodeName == "plist"){
			$childs = $element->childNodes;
			for($i=0;$i<$childs->length;$i++){
				$child = $childs->item($i);
				if($child->nodeName != "#text"){
					return plist_elementToObject($child);
				}
			}
			return null;
		}
		else if($element->nodeName == "array"){
			$rs = array();
			$childs = $element->childNodes;
			if($childs){
				for($i=0;$i<$childs->length;$i++){
					$child = $childs->item($i);
					if($child->nodeName != "#text"){
						array_push($rs, plist_elementToObject($child));
					}
				}
			}
			return $rs;
		}
		else if($element->nodeName == "dict"){
			$rs = array();
			$childs = $element->childNodes;
			if($childs){
				$keyChild = null;
				for($i=0;$i<$childs->length;$i++){
					
					$child = $childs->item($i);
					
					if($child->nodeName != "#text"){
						if($keyChild == null){
							$keyChild = $child;
						}
						else{
							$rs[plist_elementToTextContext($keyChild)] = plist_elementToObject($child);
							$keyChild = null;
						}
					}
				}
			}
			return $rs;
		}
		else if($element->nodeName == "string" || $element->nodeName == "date"){
			return plist_elementToTextContext($element);
		}
		else if($element->nodeName == "integer"){
			return intval(plist_elementToTextContext($element));
		}
		else if($element->nodeName == "real" || $element->nodeName == "number"){
			return doubleval(plist_elementToTextContext($element));
		}
		else if($element->nodeName == "true"){
			return true;
		}
		else if($element->nodeName == "false"){
			return false;
		}
	}
	return null;
}

function plist_decode($source){
	$document = new DOMDocument();
	$document->loadXML($source);
	return plist_elementToObject($document->documentElement);
}

?>