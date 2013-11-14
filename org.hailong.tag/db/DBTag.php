<?php
/**
 * 标签
 * @author zhanghailong
 *
 */
class DBTag extends DBEntity{
	
	/**
	 * 标签ID
	 * @var int
	 */
	public $tid;
	/**
	 * 标签
	 * @var String
	 */
	public $tag;
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
		return array("tid");
	}
	
	public static function autoIncrmentFields(){
		return array("tid");
	}
	
	public static function tableName(){
		return "hl_tag";
	}
	
	public static function tableField($field){
		return $field;
	}
	
	public static function tableFieldType($field){		
		if($field == "tid"){
			return "BIGINT NOT NULL";
		}
		if($field == "tag"){
			return "VARCHAR(64) NULL";
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