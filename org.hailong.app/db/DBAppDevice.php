<?php
/**
 * 应用设备
 * @author zhanghailong
 *
 */
class DBAppDevice extends DBEntity{
	
	/**
	 * 应用设备ID
	 * @var int
	 */
	public $adid;
	/**
	 * 应用ID
	 * @var int
	 */
	public $appid;
	/**
	 * 设备ID
	 * @var int
	 */
	public $did;
	/**
	 * Token
	 * @var String
	 */
	public $token;
	/**
	 * 应用版本
	 * @var String
	 */
	public $version;
	/**
	 * 编译版本
	 * @var String
	 */
	public $build;
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
		return array("adid");
	}
	
	public static function autoIncrmentFields(){
		return array("adid");
	}
	
	public static function tableName(){
		return "hl_app_device";
	}
	
	public static function tableField($field){
		return $field;
	}
	
	public static function tableFieldType($field){		
		if($field == "adid"){
			return "INT NOT NULL";
		}
		if($field == "appid"){
			return "INT NULL";
		}
		if($field == "did"){
			return "INT NULL";
		}
		if($field == "token"){
			return "VARCHAR(128) NULL";
		}
		if($field == "version"){
			return "VARCHAR(32) NULL";
		}
		if($field == "build"){
			return "VARCHAR(32) NULL";
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
		return array("hl_app_device_appid"=>array(array("field"=>"appid","order"=>"asc"))
		,"hl_app_device_did"=>array(array("field"=>"did","order"=>"asc"))
		,"hl_app_device_token"=>array(array("field"=>"token","order"=>"asc")));
	}
	
}

?>