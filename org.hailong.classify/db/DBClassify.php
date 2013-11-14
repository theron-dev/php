<?php

define("DBClassifyTargetDefault",0);

/**
 * 分类表
 * @author zhanghailong
 */
class DBClassify extends DBEntity{
	/**
	 * 分类ID
	 * @var int
	 */
	public $cid;
	/**
	 * 父级分类ID
	 * @var int
	 */
	public $pcid;
	/**
	 * 分类目标
	 * @var int
	 */
	public $target;
	/**
	 * 分类图
	 * @var String
	 */
	public $logo;
	/**
	 * 分类标题
	 * @var String
	 */
	public $title;
	/**
	 * 是否是删除的
	 * @var int
	 */
	public $deleted;
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
		return array("cid");
	}
	
	public static function autoIncrmentFields(){
		return array("cid");
	}
	
	public static function tableName(){
		return "hl_classify";
	}
	
	public static function tableField($field){
		return $field;
	}
	
	public static function tableFieldType($field){		
		if($field == "cid"){
			return "BIGINT NOT NULL";
		}
		if($field == "pcid"){
			return "BIGINT NULL";
		}
		if($field == "target"){
			return "INT DEFAULT 0";
		}
		if($field == "logo"){
			return "VARCHAR(256) NULL";
		}
		if($field == "title"){
			return "VARCHAR(128) NULL";
		}
		if($field == "deleted"){
			return "INT NULL";
		}
		if($field == "updateTime"){
			return "INT(11) NULL";
		}
		if($field == "createTime"){
			return "INT(11) NULL";
		}
		return "VARCHAR(45) NULL";
	}
	
	public static function targetTitle($target){
		switch($target){
			case DBClassifyTargetDefault:
				return "默认";
		}
		return false;
	}
	
	public static function targets(){
		return array(array("text"=>"默认","value"=>DBClassifyTargetDefault));
	}
}

?>