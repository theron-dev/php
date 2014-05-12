<?php

/**
 * 计数器
 * @author zhanghailong
 *
 */
class DBCounter extends DBEntity{
	
	/**
	 *　计数器ID
	 * @var int
	 */
	public $cid;
	/**
	 * 目标用户ID
	 * @var int
	 */
	public $tuid;
	/**
	 * 目标
	 * @var String
	 */
	public $target;
	/**
	 * 目标ID
	 * @var int
	 */
	public $tid;

	/**
	 * 来源用户ID
	 * @var int
	 */
	public $fuid;
	/**
	 * 来源类型
	 * @var int
	 */
	public $ftype;
	/**
	 * 来源ID
	 * @var int
	 */
	public $fid;

	/**
	 * 数量
	 * @var int
	 */
	public $count;
	
	/**
	 * 创建时间
	 * @var int
	 */
	public $createTime;
	
	public function __construct(){

	}
	
	public static function primaryKeys(){
		return array("cid");
	}
	
	public static function autoIncrmentFields(){
		return array("cid");
	}
	
	public static function tableName(){
		return "hl_counter";
	}
	
	public static function tableField($field){
		return $field;
	}
	
	public static function tableFieldType($field){
		if($field == "cid"){
			return "BIGINT NOT NULL";
		}

		if($field == "tuid"){
			return "BIGINT NULL";
		}
		
		if($field == "target"){
			return "VARCHAR(64) NULL";
		}
		
		if($field == "ttid"){
			return "BIGINT NULL";
		}
		
		if($field == "fuid"){
			return "BIGINT NULL";
		}
		
		if($field == "ftype"){
			return "INT NULL";
		}
		
		if($field == "fid"){
			return "BIGINT NULL";
		}
		
		if($field == "count"){
			return "INT NULL";
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
		return array("tuid"=>array(array("field"=>"tuid","order"=>"asc")),"target"=>array(array("field"=>"target","order"=>"asc")));
	}
	
}

?>