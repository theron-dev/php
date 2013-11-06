<?php

define("DBTimelineEntityTypeNone",0);

/**
 * 时间线
 * @author zhanghailong
 *
 */
class DBTimeline extends DBEntity{
	/**
	 * 时间线ID
	 * @var int
	 */
	public $tlid;
	/**
	 * 用户ID
	 * @var int
	 */
	public $uid;
	/**
	 * 实体类型
	 * @var DBTimelineEntityType
	 */
	public $etype;
	/**
	 * 实体ID
	 * @var int
	 */
	public $eid;
	/**
	 * 时间戳
	 * @var int
	 */
	public $timestamp;
	
	public static function primaryKeys(){
		return array("tlid");
	}
	
	public static function autoIncrmentFields(){
		return array("tlid");
	}
	
	public static function tableName(){
		return "hl_timeline";
	}
	
	public static function tableField($field){
		return $field;
	}
	
	public static function tableFieldType($field){		
		if($field == "tlid"){
			return "BIGINT NOT NULL";
		}
		if($field == "uid"){
			return "BIGINT NULL";
		}
		if($field == "etype"){
			return "INT NULL";
		}		
		if($field == "eid"){
			return "INT NULL";
		}
		if($field == "timestamp"){
			return "INT NULL";
		}
		return "VARCHAR(45) NULL";
	}
	
}

?>