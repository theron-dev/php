<?php

define("DBGoodsClassifyTypeNone",0);
define("DBGoodsClassifyTypeForce",1);

/**
 * 物品分类表
 * @author zhanghailong
 *
 */
class DBGoodsClassify extends DBEntity{
	
	/**
	 * 物品分类ID
	 * @var int
	 */
	public $gcid;
	/**
	 * 物品ID
	 * @var int
	 */
	public $gid;
	/**
	 * 分类ID
	 * @var int
	 */
	public $cid;
	/**
	 * 分类类型
	 * @var int
	 */
	public $type;
	/**
	 * 创建时间
	 * @var int
	 */
	public $createTime;
	
	public static function primaryKeys(){
		return array("gcid");
	}
	
	public static function autoIncrmentFields(){
		return array("gcid");
	}
	
	public static function tableName(){
		return "hl_goods_classify";
	}
	
	public static function tableField($field){
		return $field;
	}
	
	public static function tableFieldType($field){		
		
		if($field == "gcid"){
			return "BIGINT NOT NULL";
		}
		if($field == "gid"){
			return "BIGINT NULL";
		}
		if($field == "cid"){
			return "BIGINT NULL";
		}
		if($field == "type"){
			return "SMALLINT NULL";
		}
		if($field == "createTime"){
			return "INT NULL";
		}
		return "VARCHAR(45) NULL";
	}
	
}

?>