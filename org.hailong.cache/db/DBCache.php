<?php

/**
 * 缓存
 * @author hailongz
 *
 */
class DBCache extends DBEntity{
	
	/**
	 * 缓存ID
	 * @var int
	 */
	public $cid;
	/**
	 * 标示路径 account/auth
	 * @var String
	 */
	public $path;
	/**
	 * 值
	 * @var String
	 */
	public $value;
	/**
	 * 超时时间
	 * @var int
	 */
	public $expire;
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
		return "hl_cache";
	}
	
	public static function tableField($field){
		return $field;
	}
	
	public static function tableFieldType($field){		
		if($field == "cid"){
			return "BIGINT NOT NULL";
		}
		if($field == "path"){
			return "VARCHAR(256) NULL";
		}
		if($field == "value"){
			return "TEXT NULL";
		}
		if($field == "expire"){
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