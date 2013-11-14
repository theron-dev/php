<?php

define("DBATUserEntityTypeNone",0);
define("DBATUserEntityTypeShare",1);

define("DBATUserTargetTypeNone",0);
define("DBATUserTargetTypeShare",1);
define("DBATUserTargetTypeComment",2);

/**
 * @用户表
 * @author zhanghailong
 *
 */
class DBATUser extends DBEntity{
	
	/**
	 * @用户ID
	 * @var int
	 */
	public $atuid;
	/**
	 * 用户ID 发起者
	 * @var int
	 */
	public $uid;
	/**
	 * 目标用户ID 接收者
	 * @var int
	 */
	public $tuid;
	/**
	 * 实体类型
	 * @var int
	 */
	public $etype;
	/**
	 * 实体ID
	 * @var int
	 */
	public $eid;
	/**
	* 目标类型
	* @var int
	*/
	public $ttype;
	/**
	 * 目标ID
	 * @var int
	 */
	public $tid;
	/**
	 * 创建时间
	 * @var int
	 */
	public $createTime;
	
	public static function primaryKeys(){
		return array("atuid");
	}
	
	public static function autoIncrmentFields(){
		return array("atuid");
	}
	
	public static function tableName(){
		return "hl_atuser";
	}
	
	public static function tableField($field){
		return $field;
	}
	
	public static function tableFieldType($field){		
	
		if($field == "atuid"){
			return "BIGINT NOT NULL";
		}
		if($field == "tuid"){
			return "BIGINT NULL";
		}
		if($field == "uid"){
			return "BIGINT NULL";
		}
		if($field == "etype"){
			return "INT NULL";
		}
		if($field == "eid"){
			return "BIGINT NULL";
		}
		if($field == "ttype"){
			return "INT NULL";
		}
		if($field == "tid"){
			return "BIGINT NULL";
		}
		if($field == "createTime"){
			return "INT(11) NULL";
		}
		return "VARCHAR(45) NULL";
	}
	
}

?>