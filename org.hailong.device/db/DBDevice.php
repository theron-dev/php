<?php

define("DeviceTypeIOS",1);
define("DeviceTypeAndroid",2);
define("DeviceTypeJ2me",3);
define("DeviceTypeP7",4);

class DBDevice extends DBEntity{
	
	public $did;
	public $unique;
	public $type;
	public $name;
	public $systemName;
	public $systemVersion;
	public $model;
	public $updateTime;
	public $createTime;
	
	public static function primaryKeys(){
		return array("did");
	}
	
	public static function autoIncrmentFields(){
		return array("did");
	}
	
	public static function tableName(){
		return "hl_device";
	}
	
	public static function tableField($field){
		return $field;
	}
	
	public static function tableFieldType($field){		
		if($field == "did"){
			return "BIGINT NOT NULL";
		}
		if($field == "unique"){
			return "VARCHAR(256) NULL";
		}
		if($field == "name"){
			return "VARCHAR(1024) NULL";
		}
		if($field == "systemName"){
			return "VARCHAR(1024) NULL";
		}
		if($field == "systemVersion"){
			return "VARCHAR(32) NULL";
		}
		if($field == "model"){
			return "VARCHAR(32) NULL";
		}
		if($field == "updateTime"){
			return "INT(11) NULL";
		}
		if($field == "createTime"){
			return "INT(11) NULL";
		}
		return "VARCHAR(45) NULL";
	}
	
}

?>