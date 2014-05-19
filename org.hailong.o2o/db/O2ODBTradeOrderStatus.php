<?php

/**
 * 交易订单状态
 * @author zhanghailong
 *
 */
class O2ODBTradeOrderStatus extends DBEntity{
	
	/**
	 *　交易订单状态ID
	 * @var int
	 */
	public $osid;
	/**
	 *　交易订单ID
	 * @var int
	 */
	public $oid;
	/**
	 * 用户ID
	 * @var int
	 */
	public $uid;
	/**
	 * 状态
	 * @var int
	 */
	public $status;
	/**
	 * 备注
	 * @var String
	 */
	public $remark;
	/**
	 * 创建时间
	 * @var int
	 */
	public $createTime;
	
	public static function primaryKeys(){
		return array("osid");
	}
	
	public static function autoIncrmentFields(){
		return array("osid");
	}
	
	public static function tableName(){
		return "o2o_trade_order_status";
	}
	
	public static function tableField($field){
		return $field;
	}
	
	public static function tableFieldType($field){
		if($field == "osid"){
			return "BIGINT NOT NULL";
		}
		if($field == "oid"){
			return "BIGINT NULL";
		}
		if($field == "uid"){
			return "BIGINT NULL";
		}
		if($field == "status"){
			return "INT NULL";
		}
		if($field == "remark"){
			return "VARCHAR(256) NULL";;
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
		return array("oid"=>array(array("field"=>"oid","order"=>"asc")));
	}
	
}

?>