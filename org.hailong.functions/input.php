<?php 

function input(){
	$contentType = isset($_SERVER["CONTENT_TYPE"])? trim($_SERVER["CONTENT_TYPE"]):null;

	if(strpos($contentType, "application/x-www-form-urlencoded") === false){
		$content = file_get_contents("php://input");
		if($content && $contentType){
			if($contentType == "plist"){
				return plist_decode($content);
			}
			else if($contentType == "mcrypt"){
				
				$key = substr(MCRYPT_KEY, 0, 24);
				
				$d = mcrypt_decrypt(MCRYPT_3DES, $key, $content, MCRYPT_MODE_ECB);

				$len = strlen($d);
				
				$pad = ord ( substr($d,$len -1,1) );
				
				$d = substr($d,0,$len -$pad);
				
		        $rs = array();
		        
		        $args = split("&", $d);
		        
		        foreach($args as $arg){
		        	if(strlen($arg) >0){
		        		$kv = split("=",$arg);
		        		$key = $kv[0];
		        		$value = null;
		        		if(count($kv) >1){
		        			$value = urldecode($kv[1]);
		        		}
		        		if($value && $key){
		        			$rs[$key] =  $value;
		        		}
		        	}
		        }
		        
		        return $rs;
			}
			else{
				return json_decode($content,true);
			}
		}
	}
	return  null;
	
}

?>