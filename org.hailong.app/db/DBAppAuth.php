<?php
/**
 * 应用凭证
 * @author zhanghailong
 *
 */
class DBAppAuth extends DBEntity{
	
	/**
	 * 凭证ID
	 * @var int
	 */
	public $aaid;
	/**
	 * 应用ID
	 * @var int
	 */
	public $appid;
	/**
	* 用户ID
	* @var int
	*/
	public $uid;
	/**
	 * 设备ID
	 * @var int
	 */
	public $did;
	/**
	 * 凭证标识
	 * @var String
	 */
	public $token;
	/**
	 * 凭证验证码
	 * @var String
	 */
	public $secret;
	/**
	 * 验证码
	 * @var String
	 */
	public $verifyCode;
	/**
	 * 客户签名
	 * @var String
	 */
	public $sign;
	/**
	 * 设置
	 * @var String
	 */
	public $setting;
	/**
	 * 最后修改时间
	 * @var int
	 */
	public $updateTime;
	/**
	 * 创建时间
	 * @var int
	 */
	public $createTime;
	
	public static function primaryKeys(){
		return array("aaid");
	}
	
	public static function autoIncrmentFields(){
		return array("aaid");
	}
	
	public static function tableName(){
		return "hl_app_auth";
	}
	
	public static function tableField($field){
		return $field;
	}
	
	public static function tableFieldType($field){		
		if($field == "aaid"){
			return "INT NOT NULL";
		}
		if($field == "appid"){
			return "INT NULL";
		}
		if($field == "uid"){
			return "INT NULL";
		}
		if($field == "did"){
			return "INT NULL";
		}
		if($field == "token"){
			return "VARCHAR(128) NULL";
		}
		if($field == "secret"){
			return "VARCHAR(128) NULL";
		}
		if($field == "verifyCode"){
			return "VARCHAR(32) NULL";
		}
		if($field == "sign"){
			return "VARCHAR(128) NULL";
		}
		if($field == "setting"){
			return "VARCHAR(256) NULL";
		}
		if($field == "createTime"){
			return "INT(11) NULL";
		}
		if($field == "updateTime"){
			return "INT(11) NULL";
		}
		return "VARCHAR(45) NULL";
	}
	
}

?>