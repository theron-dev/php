<?php

define("AccountPasswordToken","cdXDk49k23-");
define("AccountGenneratePasswordToken","1234567890qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLMNBVCXZ");
define("AccountGennerateVerifyToken","1234567890123456789067802489283767480987567432");
define("AccountStateNone",0);
define("AccountStateGenerated",1);
define("AccountStateDisabled",100);

class DBAccount extends DBEntity{
	
	public $uid;
	public $account;
	public $tel;
	public $email;
	public $weibo_uid;
	public $qq_uid;
	public $taobao_uid;
	public $alipay_uid;
	public $kaixin_uid;
	public $renren_uid;
	public $facebook_uid;
	public $twitter_uid;
	public $douban_uid;
	public $title;
	public $password;
	public $state;
	public $email_verify;
	public $tel_verify;
	public $loginTime;
	public $updateTime;
	public $createTime;
	
	public function __construct(){
		$this->uid = 0;
		$this->state = AccountStateNone;
		$this->updateTime = time();
		$this->createTime = time();
	}
	
	public static function primaryKeys(){
		return array("uid");
	}
	
	public static function autoIncrmentFields(){
		return array("uid");
	}
	
	public static function tableName(){
		return "hl_account";
	}
	
	public static function tableField($field){
		return $field;
	}
	
	public static function tableFieldType($field){		
		if($field == "uid"){
			return "BIGINT NOT NULL";
		}
		if($field == 'account'){
			return "VARCHAR(256) NULL";
		}
		if($field == "tel"){
			return "VARCHAR(64) NULL";
		}
		if($field == "email"){
			return "VARCHAR(256) NULL";
		}
		if($field == "weibo_uid"){
			return "VARCHAR(128) NULL";
		}
		if($field == "qq_uid"){
			return "VARCHAR(128) NULL";
		}
		if($field == "taobao_uid"){
			return "VARCHAR(128) NULL";
		}
		if($field == "alipay_uid"){
			return "VARCHAR(128) NULL";
		}
		if($field == "kaixin_uid"){
			return "VARCHAR(128) NULL";
		}
		if($field == "renren_uid"){
			return "VARCHAR(128) NULL";
		}
		if($field == "facebook_uid"){
			return "VARCHAR(128) NULL";
		}
		if($field == "twitter_uid"){
			return "VARCHAR(128) NULL";
		}
		if($field == "douban_uid"){
			return "VARCHAR(128) NULL";
		}
		if($field == "title"){
			return "VARCHAR(128) NULL";
		}
		if($field == "password"){
			return "VARCHAR(128) NULL";
		}
		if($field == "state"){
			return "INT NULL";
		}
		if($field == "email_verify"){
			return "VARCHAR(32) NULL";
		}
		if($field == "tel_verify"){
			return "VARCHAR(32) NULL";
		}
		if($field == "loginTime"){
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
	
	public static function encodePassword($password){
		return md5(AccountPasswordToken.$password.AccountPasswordToken);
	}
	
	public static function generatedPassword(){
		$password = "";
		$len = strlen(AccountGenneratePasswordToken);
		srand(time());
		for($i=0;$i<6;$i++){
			$password .= substr(AccountGenneratePasswordToken, rand(0, $len -1),1);
		}
		return $password;
	}
	
	public static function generatedVerify(){
		$verify = "";
		$len = strlen(AccountGennerateVerifyToken);
		srand(time());
		for($i=0;$i<4;$i++){
			$verify .= substr(AccountGennerateVerifyToken, rand(0, $len -1),1);
		}
		return $verify;
	}
	
	public static function defaultEntitys(){
		$admin = new DBAccount();
		$admin->account = "admin";
		$admin->title = "admin";
		$admin->password = DBAccount::encodePassword("admin");
		$admin->updateTime = time();
		$admin->createTime = time();
		return array($admin);
	}
	
	public static function indexs(){
		return array("hl_account_account"=>array(array("field"=>"account","order"=>"asc"))
			,"hl_account_tel"=>array(array("field"=>"tel","order"=>"asc"))
			,"hl_account_email"=>array(array("field"=>"email","order"=>"asc")));
	}
}

?>