<?php
/**
 * 应用程序
 * @author zhanghailong
 *
 */
class DBApp extends DBEntity{
	
	/**
	 * 应用ID
	 * @var int
	 */
	public $appid;
	/**
	 * 验证码
	 * @var int
	 */
	public $secret;
	/**
	 * 创建者ID
	 * @var int
	 */
	public $uid;
	/**
	 * 标题
	 * @var String
	 */
	public $title;
	/**
	 * 说明
	 * @var String
	 */
	public $description;
	/**
	 * 创建时间
	 * @var int
	 */
	public $createTime;
	
	public static function primaryKeys(){
		return array("appid");
	}
	
	public static function autoIncrmentFields(){
		return array("appid");
	}
	
	public static function tableName(){
		return "hl_app";
	}
	
	public static function tableField($field){
		return $field;
	}
	
	public static function tableFieldType($field){		
		if($field == "appid"){
			return "BIGINT NOT NULL";
		}
		if($field == "secret"){
			return "INT NULL";
		}
		if($field == "uid"){
			return "BIGINT NULL";
		}
		if($field == "title"){
			return "VARCHAR(256) NULL";
		}
		if($field == "description"){
			return "VARCHAR(1024) NULL";
		}
		if($field == "createTime"){
			return "INT(11) NULL";
		}
		return "VARCHAR(45) NULL";
	}
	
}

?>