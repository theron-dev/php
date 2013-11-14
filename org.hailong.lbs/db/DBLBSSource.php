<?php

define("DBLBSSourceTypeNone",0);
define("DBLBSSourceTypeUser",1);
define("DBLBSSourceTypePublish",2);

/**
 * 本地服务源
 * @author zhanghailong
 *
 */
class DBLBSSource extends DBEntity{
	
	/**
	 * 源ID
	 * @var long
	 */
	public $ssid;
	/**
	 * 来源类型
	 * @var DBLBSSourceType
	 */
	public $stype;
	/**
	 * 来源ID
	 * @var int
	 */
	public $sid;
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
	 * 最后查询时间
	 * @var int
	 */
	public $searchTime;
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
		return array("ssid");
	}
	
	public static function autoIncrmentFields(){
		return array("ssid");
	}
	
	public static function tableName(){
		return "hl_lbs_source";
	}
	
	public static function tableField($field){
		return $field;
	}
	
	public static function tableFieldType($field){		
		if($field == "ssid"){
			return "BIGINT NOT NULL";
		}
		if($field == "stype"){
			return "INT NULL";
		}
		if($field == "sid"){
			return "BIGINT NULL";
		}
		if($field == "latitude"){
			return "DOUBLE NULL";
		}
		if($field == "longitude"){
			return "DOUBLE NULL";
		}
		if($field == "searchTime"){
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
			"updateTime"=>array(array("field"=>"updateTime","order"=>"desc"))
			,"latitude"=>array(array("field"=>"latitude","order"=>"asc"))
			,"longitude"=>array(array("field"=>"longitude","order"=>"asc"))
			,"sid"=>array(array("field"=>"sid","order"=>"asc"))
			,"stype"=>array(array("field"=>"stype","order"=>"asc"))
		);
	}
}

?>