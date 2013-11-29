<?php

define("MessageUserTypeNone",0);
define("MessageUserTypeTel",1);
define("MessageUserTypeEmail",2);
define("MessageUserTypeWeibo",3);
define("MessageUserTypeQQ",4);
define("MessageUserTypeTaobao",5);
define("MessageUserTypeAlipay",6);
define("MessageUserTypeKaixin",7);
define("MessageUserTypeRenren",8);
define("MessageUserTypeFacebook",9);
define("MessageUserTypeTwitter",10);
define("MessageUserTypeDouban",11);

/**
 * 消息用户
 * @author zhanghailong
 *
 */
class DBMessageUser extends DBEntity{
	
	/**
	 *　消息用户ID
	 * @var int
	 */
	public $muid;
	/**
	 * 用户ID
	 * @var int
	 */
	public $uid;
	/**
	 * 消息用户类型
	 * @var int
	 */
	public $type;
	/**
	 * 消息用户Key
	 * @var String
	 */
	public $key;
	/**
	 * 用户来源
	 * @var String
	 */
	public $source;
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
		return array("muid");
	}
	
	public static function autoIncrmentFields(){
		return array("muid");
	}
	
	public static function tableName(){
		return "hl_msg_message_user";
	}
	
	public static function tableField($field){
		return $field;
	}
	
	public static function tableFieldType($field){
		if($field == "muid"){
			return "BIGINT NOT NULL";
		}
		if($field == "uid"){
			return "BIGINT NULL";
		}
		if($field == "type"){
			return "INT NULL";
		}
		if($field == "key"){
			return "VARCHAR(256) NULL";
		}
		if($field == "source"){
			return "VARCHAR(128) NULL";
		}
		if($field == "updateTime"){
			return "INT NULL";
		}
		if($field == "createTime"){
			return "INT NULL";
		}
		return "VARCHAR(45) NULL";
	}
	
}

?>