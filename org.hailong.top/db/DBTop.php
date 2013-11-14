<?php

/**
 * 热门排行
 * @author zhanghailong
 *
 */
class DBTop extends DBEntity{
	
	/**
	 * 热门排行ID
	 * @var int
	 */
	public $tid;
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
		return array("tid");
	}
	
	public static function autoIncrmentFields(){
		return array("tid");
	}
	
	public static function tableName(){
		return "hl_top";
	}
	
	public static function tableField($field){
		return $field;
	}
	
	public static function tableFieldType($field){		
		if($field == "tid"){
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
		return array(
				"key"=>array(array("field"=>"key","order"=>"asc")),
				"etype"=>array(array("field"=>"etype","order"=>"asc")),
				"topCount"=>array(array("field"=>"topCount","order"=>"desc")),	
				"updateTime"=>array(array("field"=>"updateTime","order"=>"desc")),
			);
	}
	
}

?>