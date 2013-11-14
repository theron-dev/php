<?php

/**
 * URI表
 * @author zhanghailong
 *
 */
class DBURI extends DBEntity{
	
	/**
	 * URI
	 * @var int
	 */
	public $uri;
	/**
	 * URL
	 * @var String
	 */
	public $url;
	/**
	 * 浏览数
	 * @var int
	 */
	public $browseCount;
	/**
	 * 创建时间
	 * @var int
	 */
	public $createTime;
	
	public static function primaryKeys(){
		return array("uri");
	}
	
	public static function autoIncrmentFields(){
		return array("uri");
	}
	
	public static function tableName(){
		return "hl_uri";
	}
	
	public static function tableField($field){
		return $field;
	}
	
	public static function tableFieldType($field){		
		if($field == "uri"){
			return "BIGINT NOT NULL";
		}
		if($field == "url"){
			return "VARCHAR(1024) NULL";
		}
		if($field == "browseCount"){
			return "INT(11) NULL";
		}
		if($field == "createTime"){
			return "INT(11) NULL";
		}
		return "VARCHAR(45) NULL";
	}
	
	
	public static function URIEncode($uri){
		$rs = "";
		while($uri >0){
			$c = $uri % 64;
			$uri = intval($uri / 64);
			if($c < 26){
				$rs .= chr(65 + $c);
			}
			else if($c < 52){
				$rs .= chr(71 + $c);
			}
			else if($c < 62){
				$rs .= chr(- 4 + $c);
			}
			else if($c == 62){
				$rs .= "(";
			}
			else {
				$rs .= ")";
			}
		}
		return $rs;
	}
	
	public static function URIDecode($uri){
		$rs = 0;
		$len = strlen($uri);
		$m = 1;
		for($i=0;$i<$len;$i++){
			$c = ord(substr($uri, $i,1));
			
			if($c >= 65 && $c < 91 ){
				$rs += ( $c - 65 ) * $m;
				$m *= 64;
			}
			else if($c >= 97 && $c < 123 ){
				$rs += ( $c - 71 ) * $m;
				$m *= 64;
			}
			else if($c >= 48 && $c < 58 ){
				$rs += ( $c  + 4 ) * $m;
				$m *= 64;
			}
			else if($c == 40){
				$rs += 62 * $m;
				$m *= 64;
			}
			else if($c == 41){
				$rs += 63 * $m;
				$m *= 64;
			}
			else{
				return false;
			}
		}
		return $rs;
	}
}

?>