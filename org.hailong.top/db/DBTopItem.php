<?php

/**
 * 热门排行项
 * @author zhanghailong
 *
 */
class DBTopItem extends DBEntity{
	
	/**
	 * 热门排行ID
	 * @var int
	 */
	public $tiid;
	/**
	 * 标示
	 * @var String
	 */
	public $key;
	/**
	 * 实体类型
	 * @var int
	 */
	public $etype;
	/**
	 * 实体ID
	 * @var int
	 */
	public $eid;
	/**
	 * 热度
	 * @var int
	 */
	public $topCount;
	/**
	 * 创建时间
	 * @var int
	 */
	public $createTime;
	
	public static function primaryKeys(){
		return array("tiid");
	}
	
	public static function autoIncrmentFields(){
		return array("tiid");
	}
	
	public static function tableName(){
		return "hl_top_item";
	}
	
	public static function tableField($field){
		return $field;
	}
	
	public static function tableFieldType($field){		
		if($field == "tiid"){
			return "BIGINT NOT NULL";
		}
		if($field == "key"){
			return "VARCHAR(32) NULL";
		}
		if($field == "etype"){
			return "INT NULL";
		}
		if($field == "eid"){
			return "BIGINT NULL";
		}
		if($field == "topCount"){
			return "BIGINT NULL";
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
		return array(
				"key"=>array(array("field"=>"key","order"=>"asc")),
				"etype"=>array(array("field"=>"etype","order"=>"asc")),
				"eid"=>array(array("field"=>"eid","order"=>"asc")),	
				"createTime"=>array(array("field"=>"createTime","order"=>"asc")),
			);
	}
	
}

?>