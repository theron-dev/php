<?php

/**
 * 服务者
 * @author zhanghailong
 *
 */
class O2ODBProvider extends DBEntity{
	
	/**
	 *　服务者ID
	 * @var int
	 */
	public $pid;
	/**
	 * 创建时间
	 * @var int
	 */
	public $createTime;
	
	public static function primaryKeys(){
		return array("pid");
	}
	
	public static function autoIncrmentFields(){
		return array("pid");
	}
	
	public static function tableName(){
		return "o2o_provider";
	}
	
	public static function tableField($field){
		return $field;
	}
	
	public static function tableFieldType($field){
		if($field == "pid"){
			return "BIGINT NOT NULL";
		}
		if($field == "createTime"){
			return "INT NULL";
		}
		return "VARCHAR(45) NULL";
	}
	
}

?>