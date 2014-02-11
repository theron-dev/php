<?php

/**
 * 物品关键词表
 * @author zhanghailong
 */
class DBGoodsKeyword extends DBEntity{
	/**
	 * 物品关键词ID
	 * @var int
	 */
	public $gkid;
	/**
	 * 物品ID
	 * @var int
	 */
	public $gid;
	/**
	 * 标签ID
	 * @var int
	 */
	public $tid;
	/**
	 * 创建时间
	 * @var int
	 */
	public $createTime;
	
	public static function primaryKeys(){
		return array("gkid");
	}
	
	public static function autoIncrmentFields(){
		return array("gkid");
	}
	
	public static function tableName(){
		return "hl_goods_keyword";
	}
	
	public static function tableField($field){
		return $field;
	}
	
	public static function tableFieldType($field){		
		if($field == "gkid"){
			return "BIGINT NOT NULL";
		}
		if($field == "gid"){
			return "BIGINT NULL";
		}
		if($field == "tid"){
			return "BIGINT NULL";
		}
		if($field == "createTime"){
			return "INT NULL";
		}
		return "VARCHAR(45) NULL";
	}
	
}

?>