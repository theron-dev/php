<?php

define("DBLikedEntityTypeNone",0);
define("DBLikedEntityTypeShare",1);

/**
 * 喜欢表
 * @author zhanghailong
 *
 */
class DBLiked extends DBEntity{
	
	/**
	 * 格子ID
	 * @var int
	 */
	public $lid;
	/**
	 * 用户ID
	 * @var int
	 */
	public $uid;
	/**
	 * 喜欢实体类型
	 * @var int
	 */
	public $etype;
	/**
	 * 喜欢实体ID
	 * @var int
	 */
	public $eid;
	/**
	 * 删除喜欢
	 * @var int
	 */
	public $deleted;
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
		return array("lid");
	}
	
	public static function autoIncrmentFields(){
		return array("lid");
	}
	
	public static function tableName(){
		return "hl_liked";
	}
	
	public static function tableField($field){
		return $field;
	}
	
	public static function tableFieldType($field){		
	
		if($field == "lid"){
			return "BIGINT NOT NULL";
		}
		if($field == "uid"){
			return "BIGINT NULL";
		}
		if($field == "etype"){
			return "INT NULL";
		}
		if($field == "eid"){
			return "BIGINT NULL";
		}
		if($field == "deleted"){
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
	
	/**
	 * @return array("index_name"=>array(array("field"=>"field1","order"="desc"),array("field"=>"field2","order"="asc")))
	 */
	public static function indexs(){
		return array("uid"=>array(array("field"=>"uid","order"=>"asc"))
				,"etype"=>array(array("field"=>"etype","order"=>"asc"))
				,"eid"=>array(array("field"=>"eid","order"=>"desc")));
	}
}

?>