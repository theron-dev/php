<?php

/**
 * 消息附件
 * @author zhanghailong
 *
 */
class DBMessageAttach extends DBEntity{
	
	/**
	 *　消息附件ID
	 * @var int
	 */
	public $maid;
	/**
	 * 消息ID
	 * @var int
	 */
	public $mid;
	/**
	 * 资源Key
	 * @var String
	 */
	public $key;
	/**
	 * 内容类型
	 * @var String
	 */
	public $contentType;
	/**
	 * 资源URI
	 * @var String
	 */
	public $uri;
	/**
	 * 创建时间
	 * @var int
	 */
	public $createTime;
	
	public static function primaryKeys(){
		return array("maid");
	}
	
	public static function autoIncrmentFields(){
		return array("maid");
	}
	
	public static function tableName(){
		return "hl_msg_message_attach";
	}
	
	public static function tableField($field){
		return $field;
	}
	
	public static function tableFieldType($field){
		if($field == "maid"){
			return "BIGINT NOT NULL";
		}
		if($field == "mid"){
			return "BIGINT NULL";
		}
		if($field == "key"){
			return "VARCHAR(64) NULL";
		}
		if($field == "contentType"){
			return "VARCHAR(32) NULL";
		}
		if($field == "uri"){
			return "VARCHAR(128) NULL";
		}
		if($field == "createTime"){
			return "INT NULL";
		}
		return "VARCHAR(45) NULL";
	}
	
}

?>