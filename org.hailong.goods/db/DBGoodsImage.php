<?php

define("DBGoodsImageTypeNone",0);
define("DBGoodsImageTypeBody",1);

/**
 * 物品图片表
 * @author zhanghailong
 */
class DBGoodsImage extends DBEntity{
	/**
	 * 物品图片表
	 * @var int
	 */
	public $giid;
	/**
	 * 物品ID
	 * @var int
	 */
	public $gid;
	/**
	 * 类型	
	 * @var DBGoodsImageType
	 */
	public $type;
	/**
	 * 图片URL
	 * @var int
	 */
	public $url;
	/**
	 * 创建时间
	 * @var int
	 */
	public $createTime;
	
	public static function primaryKeys(){
		return array("giid");
	}
	
	public static function autoIncrmentFields(){
		return array("giid");
	}
	
	public static function tableName(){
		return "hl_goods_image";
	}
	
	public static function tableField($field){
		return $field;
	}
	
	public static function tableFieldType($field){		
		if($field == "giid"){
			return "BIGINT NOT NULL";
		}
		if($field == "gid"){
			return "BIGINT NULL";
		}
		if($field == "type"){
			return "INT NULL";
		}
		if($field == "url"){
			return "VARCHAR(256) NULL";
		}
		if($field == "createTime"){
			return "INT NULL";
		}
		return "VARCHAR(45) NULL";
	}
	
}

?>