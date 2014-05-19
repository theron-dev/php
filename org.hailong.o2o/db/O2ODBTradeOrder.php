<?php

/**
 * 交易订单
 * @author zhanghailong
 *
 */
class O2ODBTradeOrder extends DBEntity{
	
	/**
	 *　交易订单ID
	 * @var int
	 */
	public $oid;
	/**
	 *　交易实体ID
	 * @var int
	 */
	public $eid;
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
		return array("oid");
	}
	
	public static function autoIncrmentFields(){
		return array("oid");
	}
	
	public static function tableName(){
		return "o2o_trade_order";
	}
	
	public static function tableField($field){
		return $field;
	}
	
	public static function tableFieldType($field){
		if($field == "oid"){
			return "BIGINT NOT NULL";
		}
		if($field == "eid"){
			return "BIGINT NULL";
		}
		if($field == "uid"){
			return "BIGINT NULL";
		}
		if($field == "status"){
			return "INT NULL";
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