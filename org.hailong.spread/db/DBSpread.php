<?php

define("DBSpreadTypeNone",0);
define("DBSpreadTypeTaoke",1);

/**
 * 推广表
 * @author zhanghailong
 *
 */
class DBSpread extends DBEntity{
	
	/**
	 * 推广ID
	 * @var int
	 */
	public $sid;
	/**
	 * 推广类型
	 * @var DBSpreadType
	 */
	public $type;
	/**
	 * 推广名称
	 * @var String
	 */
	public $title;
	/**
	 * 推广标示
	 * @var String
	 */
	public $marked;
	/**
	 * 推广限制 0 为不限制　
	 * @var int
	 */
	public $askLimit;
	/**
	 * 索取次数
	 * @var int
	 */
	public $askCount;
	/**
	 * 修改时间
	 * @var int
	 */
	public $updateTime;
	/**
	 * 创建时间
	 * @var int
	 */
	public $createTime;
	

	public static function primaryKeys(){
		return array("sid");
	}
	
	public static function autoIncrmentFields(){
		return array("sid");
	}
	
	public static function tableName(){
		return "hl_spread";
	}
	
	public static function tableField($field){
		return $field;
	}
	
	public static function tableFieldType($field){		
		if($field == "sid"){
			return "BIGINT NOT NULL";
		}
		if($field == 'type'){
			return "INT NULL";
		}
		if($field == "title"){
			return "VARCHAR(64) NULL";
		}
		if($field == "marked"){
			return "VARCHAR(128) NULL";
		}
		if($field == "askLimit"){
			return "INT NULL";
		}
		if($field == "askCount"){
			return "INT NULL";
		}
		if($field == "updateTime"){
			return "INT NULL";
		}
		if($field == "createTime"){
			return "INT NULL";
		}
		return "VARCHAR(45) NULL";
	}
	
}

?>