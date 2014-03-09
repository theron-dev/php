<?php

/**
 * 反馈
 * @author zhanghailong
 *
 */
class DBFeedback extends DBEntity{
	
	/**
	 * 反馈 ID
	 * @var int
	 */
	public $fid;
	/**
	 * 用户ID
	 * @var int
	 */
	public $uid;
	/**
	 * 设备ID
	 * @var int
	 */
	public $did;
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
		return array("fid");
	}
	
	public static function autoIncrmentFields(){
		return array("fid");
	}
	
	public static function tableName(){
		return "hl_feedback";
	}
	
	public static function tableField($field){
		return $field;
	}
	
	public static function tableFieldType($field){
		if($field == "fid"){
			return "BIGINT NOT NULL";
		}
		if($field == "uid"){
			return "BIGINT NULL";
		}
		if($field == "did"){
			return "BIGINT NULL";
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
		if($field == "body"){
			return "TEXT NULL";
		}
		if($field == "createTime"){
			return "INT(11) NULL";
		}
		return "VARCHAR(45) NULL";
	}
	
}

?>