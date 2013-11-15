<?php

/**
 * Crash
 * @author zhanghailong
 *
 */
class DBCrash extends DBEntity{
	
	/**
	 * Crash ID
	 * @var int
	 */
	public $cid;
	/**
	 * 应用标示
	 * @var String
	 */
	public $identifier;
	/**
	 * 应用版本
	 * @var String
	 */
	public $version;
	/**
	 * 应用编译版本
	 * @var String
	 */
	public $build;
	/**
	 * 系统名
	 * @var String
	 */
	public $systemName;
	/**
	 * 系统版本
	 * @var String
	 */
	public $systemVersion;
	/**
	 * 硬件
	 * @var String
	 */
	public $model;
	/**
	 * 设备名称	
	 * @var String
	 */
	public $deviceName;
	/**
	 * 设备标示
	 * @var String
	 */
	public $deviceIdentifier;
	/**
	 * 异常对信息对象
	 * @var Object
	 */
	public $exception;
	/**
	 * 创建时间
	 * @var int
	 */
	public $createTime;
	
	public static function primaryKeys(){
		return array("cid");
	}
	
	public static function autoIncrmentFields(){
		return array("cid");
	}
	
	public static function tableName(){
		return "hl_crash";
	}
	
	public static function tableField($field){
		return $field;
	}
	
	public static function tableFieldType($field){
		if($field == "cid"){
			return "BIGINT NOT NULL";
		}
		if($field == "identifier"){
			return "VARCHAR(256) NULL";
		}
		if($field == "version"){
			return "VARCHAR(64) NULL";
		}
		if($field == "build"){
			return "VARCHAR(64) NULL";
		}
		if($field == "systemName"){
			return "VARCHAR(64) NULL";
		}
		if($field == "systemVersion"){
			return "VARCHAR(64) NULL";
		}
		if($field == "model"){
			return "VARCHAR(64) NULL";
		}
		if($field == "deviceName"){
			return "VARCHAR(128) NULL";
		}
		if($field == "deviceIdentifier"){
			return "VARCHAR(64) NULL";
		}
		if($field == "exception"){
			return "TEXT NULL";
		}
		if($field == "createTime"){
			return "INT(11) NULL";
		}
		return "VARCHAR(45) NULL";
	}
	
}

?>