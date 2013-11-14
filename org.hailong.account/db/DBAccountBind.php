<?php

define("AccountBindTypeNone",0);
define("AccountBindTypeTel",1);
define("AccountBindTypeEmail",2);
define("AccountBindTypeWeibo",3);
define("AccountBindTypeQQ",4);
define("AccountBindTypeTaobao",5);
define("AccountBindTypeAlipay",6);
define("AccountBindTypeKaixin",7);
define("AccountBindTypeRenren",8);
define("AccountBindTypeFacebook",9);
define("AccountBindTypeTwitter",10);
define("AccountBindTypeDouban",11);

/**
 * 帐号绑定
 * @author zhanghailong
 *
 */
class DBAccountBind extends DBEntity{
	
	/**
	 *　帐号绑定ID
	 * @var int
	 */
	public $ubid;
	/**
	 * 用户ID
	 * @var int
	 */
	public $uid;
	/**
	 * 类型
	 * @var AccountBindType
	 */
	public $type;
	/**
	 * 外部APP KEY
	 * @var String
	 */
	public $appKey;
	/**
	 *  外部APP SECRET
	 */
	public $appSecret;
	/**
	 *  外部用户ID
	 * @var String
	 */
	public $eid;
	/**
	 * 验证token
	 * @var String
	 */
	public $etoken;
	/**
	 * 过期时间
	 * @var int
	 */
	public $expires_in;
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
		return array("ubid");
	}
	
	public static function autoIncrmentFields(){
		return array("ubid");
	}
	
	public static function tableName(){
		return "hl_account_bind";
	}
	
	public static function tableField($field){
		return $field;
	}
	
	public static function tableFieldType($field){
		if($field == "ubid"){
			return "BIGINT NOT NULL";
		}
		if($field == "uid"){
			return "BIGINT NULL";
		}
		if($field == "type"){
			return "INT NULL";
		}
		if($field == "appKey"){
			return "VARCHAR(128) NULL";
		}
		if($field == "appKey"){
			return "VARCHAR(256) NULL";
		}
		if($field == "eid"){
			return "VARCHAR(128) NULL";
		}
		if($field == "etoken"){
			return "VARCHAR(256) NULL";
		}
		if($field == "expires_in"){
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