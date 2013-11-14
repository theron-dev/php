<?php

/**
 * 分类关键词表
 * @author zhanghailong
 */
class DBClassifyKeyword extends DBEntity{
	/**
	 * 分类关键词ID
	 * @var int
	 */
	public $ckid;
	/**
	 * 分类ID
	 * @var int
	 */
	public $cid;
	/**
	 * 标签ID
	 * @var int
	 */
	public $tid;
	/**
	 * 权重
	 * @var int
	 */
	public $weight;
	/**
	 * 创建时间
	 * @var int
	 */
	public $createTime;
	
	public static function primaryKeys(){
		return array("ckid");
	}
	
	public static function autoIncrmentFields(){
		return array("ckid");
	}
	
	public static function tableName(){
		return "hl_classify_keyword";
	}
	
	public static function tableField($field){
		return $field;
	}
	
	public static function tableFieldType($field){		
		if($field == "ckid"){
			return "BIGINT NOT NULL";
		}
		if($field == "cid"){
			return "BIGINT NULL";
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