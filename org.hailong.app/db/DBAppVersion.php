<?php


define("Platform_iOS",1);
define("Platform_Android",2);
define("Platform_J2me",3);

define("AppUpdateLevelSuggest",1);
define("AppUpdateLevelForce",2);

class DBAppVersion extends DBEntity{
	
	public $avid;
	public $appid;
	public $platform;
	public $version;
	public $updateLevel;
	public $isLastVersion;
    public $content;
    public $uri;
	public $timestamp;
	
	public static function primaryKeys(){
		return array("avid");
	}
	
	public static function autoIncrmentFields(){
		return array("avid");
	}
	
	public static function tableName(){
		return "hl_app_version";
	}
	
	public static function tableField($field){
		return $field;
	}
	
	public static function tableFieldType($field){		
		if($field == "avid"){
			return "INT NOT NULL";
		}
		if($field == "appid"){
			return "INT NULL";
		}
		if($field == "platform"){
			return "INT NULL";
		}
		if($field == "version"){
			return "VARCHAR(32) NULL";
		}
		if($field == "updateLevel"){
			return "INT NULL";
		}
		if($field == "isLastVersion"){
			return "INT NULL";
		}
        if($field == "content"){
            return "VARCHAR(512) NULL";
        }
        if($field == "uri"){
            return "VARCHAR(512) NULL";
        }
		if($field == "timestamp"){
			return "BIGINT NULL";
		}
		return "VARCHAR(45) NULL";
	}
	
}

?>