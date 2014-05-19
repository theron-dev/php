<?php

/**
 * 交易实体
 * @author zhanghailong
 *
 */
class O2ODBTradeEntity extends DBEntity{
	
	/**
	 *　交易实体ID
	 * @var int
	 */
	public $eid;
	/**
	 *　服务者ID
	 * @var int
	 */
	public $pid;
	/**
	 * 是否移除的
	 * @var unknown
	 */
	public $removed;
	/**
	 * 创建时间
	 * @var int
	 */
	public $createTime;
	
	public static function primaryKeys(){
		return array("eid");
	}
	
	public static function autoIncrmentFields(){
		return array("eid");
	}
	
	public static function tableName(){
		return "o2o_trade_entity";
	}
	
	public static function tableField($field){
		return $field;
	}
	
	public static function tableFieldType($field){
		if($field == "eid"){
			return "BIGINT NOT NULL";
		}
		if($field == "pid"){
			return "BIGINT NULL";
		}
		if($field == "removed"){
			return "INT(2) NULL";
		}
		if($field == "createTime"){
			return "INT NULL";
		}
		return "VARCHAR(45) NULL";
	}

	/**
	 * @return array("index_name"=>array(array("field"=>"field1","order"="desc"),array("field"=>"field2","order"="asc")))
	 */
	public static function indexs(){
		return array("pid"=>array(array("field"=>"pid","order"=>"asc")));
	}
}

?>