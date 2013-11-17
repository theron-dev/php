<?php

define("DBIntegralSourceTypeNone",0);
define("DBIntegralSourceTypeLike",1);
define("DBIntegralSourceTypeFollowUser",2);
define("DBIntegralSourceTypeFollowLattice",3);
define("DBIntegralSourceTypeLikeShare",4);

/**
 * 积分表
 * @author zhanghailong
 *
 */
class DBIntegral extends DBEntity{
	
	/**
	 * 积分ID
	 * @var int
	 */
	public $iid;
	/**
	 * 用户ID
	 * @var int
	 */
	public $uid;
	/**
	 * 来源用户ID
	 * @var int
	 */
	public $suid;
	/**
	 * 来源类型
	 * @var int
	 */
	public $stype;
	/**
	 * 来源ID
	 * @var int
	 */
	public $sid;
	/**
	 * 值
	 * @var double
	 */
	public $value;
	/**
	 * 创建时间
	 * @var int
	 */
	public $createTime;
	
	public static function primaryKeys(){
		return array("iid");
	}
	
	public static function autoIncrmentFields(){
		return array("iid");
	}
	
	public static function tableName(){
		return "hl_integral";
	}
	
	public static function tableField($field){
		return $field;
	}
	
	public static function tableFieldType($field){		
		
		if($field == "iid"){
			return "BIGINT NOT NULL";
		}
		if($field == "uid"){
			return "BIGINT NULL";
		}
		if($field == "suid"){
			return "BIGINT NULL";
		}
		if($field == "stype"){
			return "INT NULL";
		}
		if($field == "sid"){
			return "BIGINT NULL";
		}
		if($field == "value"){
			return "DOUBLE NULL";
		}
		if($field == "createTime"){
			return "INT NULL";
		}
		return "VARCHAR(45) NULL";
	}
	
}

?>