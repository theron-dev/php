<?php

/**
 * 用户关系
 * @author zhanghailong
 *
 */
class DBUserRelation extends DBEntity{
	
	/**
	 * 用户关系ID
	 * @var int
	 */
	public $urid;
	/**
	 * 用户ID
	 * @var int
	 */
	public $uid;
	/**
	 * 联系人ID
	 * @var int
	 */
	public $fuid;
	/**
	 * 关系来源
	 * @var int
	 */
	public $source;
	/**
	 * 权重
	 * @var int
	 */
	public $weight;
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
		return array("urid");
	}
	
	public static function autoIncrmentFields(){
		return array("ruid");
	}
	
	public static function tableName(){
		return "hl_user_relation";
	}
	
	public static function tableField($field){
		return $field;
	}
	
	public static function tableFieldType($field){		
		if($field == "urid"){
			return "BIGINT NOT NULL";
		}
		if($field == 'uid'){
			return "BIGINT NULL";
		}
		if($field == "fuid"){
			return "BIGINT NULL";
		}
		if($field == "source"){
			return "VARCHAR(32) NULL";
		}
		if($field == "weight"){
			return "INT(11) NULL";
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