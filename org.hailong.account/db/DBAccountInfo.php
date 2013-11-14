<?php

define("AccountInfoKeyLogo","logo");
define("AccountInfoKeySex","sex");
define("AccountInfoKeyBirthday","birthday");
define("AccountInfoKeyCity","city");
define("AccountInfoKeyNick","nick");

/**
 * 帐号信息
 * @author zhanghailong
 *
 */
class DBAccountInfo extends DBEntity{
	
	/**
	 *　
	 * @var int
	 */
	public $uiid;
	/**
	 * 用户ID
	 * @var int
	 */
	public $uid;
	/**
	 * 信息标示
	 * @var String
	 */
	public $key;
	/**
	 * 值
	 * @var String
	 */
	public $value;
	/**
	 * 大值
	 * @var String
	 */
	public $text;
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
	
	public function __construct(){

	}
	
	public static function primaryKeys(){
		return array("uiid");
	}
	
	public static function autoIncrmentFields(){
		return array("uiid");
	}
	
	public static function tableName(){
		return "hl_account_info";
	}
	
	public static function tableField($field){
		return $field;
	}
	
	public static function tableFieldType($field){
		if($field == "uiid"){
			return "BIGINT NOT NULL";
		}
		if($field == "uid"){
			return "BIGINT NULL";
		}
		if($field == "key"){
			return "VARCHAR(64) NULL";
		}
		if($field == "value"){
			return "VARCHAR(256) NULL";
		}
		if($field == "text"){
			return "TEXT NULL";
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
		return array("hl_account_info_uid"=>array(array("field"=>"uid","order"=>"asc"))
			,"hl_account_info_key"=>array(array("field"=>"key","order"=>"asc")));
	}
}

?>