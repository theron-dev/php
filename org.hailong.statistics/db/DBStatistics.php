<?php

/**
 * 统计表基类
 * @author zhanghailong
 *
 */
class DBStatistics extends DBEntity{
	
	/**
	 * 统计信息ID
	 * @var int
	 */
	public $sid;
	/**
	 * 来源用户
	 * @var int
	 */
	public $uid;
	/**
	 * 信息来源
	 * @var String
	 */
	public $source;
	/**
	 * 会话ID
	 * @var String
	 */
	public $sessionId;
	/**
	 * 统计目标
	 * @var String
	 */
	public $target;
	/**
	 * 归类时间
	 * @var int
	 */
	public $classifyTime;
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
		return array("sid");
	}
	
	public static function autoIncrmentFields(){
		return array("sid");
	}
	
	public static function tableName(){
		return "hl_statistics";
	}
	
	public static function tableField($field){
		return $field;
	}
	
	public static function tableFieldType($field){		
		if($field == "sid"){
			return "BIGINT NOT NULL";
		}
		if($field == "uid"){
			return "BIGINT NULL";
		}
		if($field == "source"){
			return "VARCHAR(64) NULL";
		}
		if($field == "session"){
			return "VARCHAR(128) NULL";
		}
		if($field == "target"){
			return "VARCHAR(64) NULL";
		}
		if($field == "classifyTime"){
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
	
	public static function indexs(){
		return array(
			"index_createTime"=>array("createTime"),
		);
	}
}

?>