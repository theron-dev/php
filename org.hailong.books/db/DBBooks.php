<?php

define("DBBooksMoneyUnitRMB",0);
define("DBBooksMoneyUnitUS",1);

define("DBBooksTypeExpend",0);
define("DBBooksTypeIncome",1);

/**
 * 账本
 * @author zhanghailong
 *
 */
class DBBooks extends DBEntity{
	
	/**
	 * 账本ID
	 * @var int
	 */
	public $bid;
	
	/**
	 * 用户ID	
	 * @var int
	 */
	public $uid;
	
	/**
	 * 支付金额
	 * @var double
	 */
	public $payMoney;
	
	/**
	 * 实际支出
	 * @var double
	 */
	public $expendMoney;
	
	/**
	 * 单位
	 * @var DBBooksMoneyUnit
	 */
	public $unit;
	
	/**
	 * 类型
	 * @var DBBooksType
	 */
	public $type;
	/**
	 * 纬度
	 * @var latitude
	 */
	public $latitude;
	
	/**
	 * 经度
	 * @var longitude
	 */
	public $longitude;
	
	/**
	 * 其他信息 json
	 * @var String
	 */
	public $body;
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
		return array("bid");
	}
	
	public static function autoIncrmentFields(){
		return array("bid");
	}
	
	public static function tableName(){
		return "hl_books";
	}
	
	public static function tableField($field){
		return $field;
	}
	
	public static function tableFieldType($field){		
		if($field == "bid"){
			return "BIGINT NOT NULL";
		}
		if($field == "uid"){
			return "BIGINT NULL";
		}
		if($field == "payMoney"){
			return "DOUBLE NULL";
		}
		if($field == "expendMoney"){
			return "DOUBLE NULL";
		}
		if($field == "unit"){
			return "INT NULL";
		}
		if($field == "type"){
			return "INT NULL";
		}
		if($field == "latitude"){
			return "DOUBLE NULL";
		}
		if($field == "longitude"){
			return "DOUBLE NULL";
		}
		if($field == "body"){
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
	
	/**
	 * @return array("index_name"=>array(array("field"=>"field1","order"="desc"),array("field"=>"field2","order"="asc")))
	 */
	public static function indexs(){
		return array("uid"=>array(array("field"=>"uid","order"=>"asc")),"createTime"=>array(array("field"=>"createTime","order"=>"asc")));
	}
	
}

?>