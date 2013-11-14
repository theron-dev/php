<?php

define("LogLevelNone",0);

define("LogLevelError",1<<0);
define("LogLevelInfo",1<<1);
define("LogLevelDebug",1<<2);

define("LogLevelAll",0x7ffff);

/**
 * 日志
 * @author zhanghailong
 *
 */
class DBLog extends DBEntity{
	
	/**
	 *　日志ID
	 * @var int
	 */
	public $lid;
	/**
	* 级别
	* @var int
	*/
	public $level;
	/**
	 * TAG
	 * @var String
	 */
	public $tag;
	/**
	 * 来源
	 * @var String
	 */
	public $source;
	/**
	 * 内容
	 * @var String
	 */
	public $body;
	/**
	 * 创建时间
	 * @var int
	 */
	public $createTime;
	
	public static function primaryKeys(){
		return array("lid");
	}
	
	public static function autoIncrmentFields(){
		return array("lid");
	}
	
	public static function tableName(){
		return "hl_log";
	}
	
	public static function tableField($field){
		return $field;
	}
	
	public static function tableFieldType($field){
		if($field == "lid"){
			return "BIGINT NOT NULL";
		}
		if($field == "level"){
			return "INT NULL";
		}
		if($field == "tag"){
			return "VARCHAR(128) NULL";
		}
		if($field == "source"){
			return "VARCHAR(64) NULL";
		}
		if($field == "body"){
			return "VARCHAR(256) NULL";
		}
		if($field == "createTime"){
			return "INT(11) NULL";
		}
		return "VARCHAR(45) NULL";
	}
	
}

?>