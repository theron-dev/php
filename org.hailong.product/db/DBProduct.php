<?php

define("DBProductStateNone",0);
define("DBProductStateSale",1);
define("DBProductStateSoldOut",2);
define("DBProductStateDisabled",3);

/**
 * 商品
 * @author zhanghailong
 *
 */
class DBProduct extends DBEntity{
	
	/**
	 *　商品ID
	 * @var int
	 */
	public $pid;
	/**
	 * 用户ID
	 * @var int
	 */
	public $uid;
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
	 * 标价
	 * @var double
	 */
	public $price;
	/**
	 * 出售价格
	 * @var double
	 */
	public $salePrice;
	/**
	 * 可售数量， -1为不限制
	 * @var int
	 */
	public $count;
	/**
	 * 发布数
	 * @var int
	 */
	public $publishCount;
	/**
	 * 状态
	 * @var int
	 */
	public $state;
	/**
	 * 出售时间
	 * @var int
	 */
	public $saleTime;
	/**
	 * 结束时间 0 为不限制
	 * @var int
	 */
	public $endTime;
	/**
	 * 目标
	 * @var String
	 */
	public $target;
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
		return array("pid");
	}
	
	public static function autoIncrmentFields(){
		return array("pid");
	}
	
	public static function tableName(){
		return "hl_product";
	}
	
	public static function tableField($field){
		return $field;
	}
	
	public static function tableFieldType($field){
		if($field == "pid"){
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
		if($field == "count"){
			return "INT(11) NULL";
		}
		if($field == "publishCount"){
			return "INT(11) NULL";
		}
		if($field == "price"){
			return "DOUBLE NULL";
		}
		if($field == "salePrice"){
			return "DOUBLE NULL";
		}
		if($field == "state"){
			return "INT NULL";
		}
		if($field == "saleTime"){
			return "INT NULL";
		}
		if($field == "endTime"){
			return "INT NULL";
		}
		if($field == "target"){
			return "VARCHAR(64) NULL";
		}
		if($field == "updateTime"){
			return "INT NULL";
		}
		if($field == "createTime"){
			return "INT NULL";
		}
		return "VARCHAR(45) NULL";
	}
	
	public function __construct(){
		$this->count = -1;
		$this->state = DBProductStateNone;
		$this->endTime = 0;
		$this->updateTime = $this->createTime = time();
	}
	
	/**
	 * @return array("index_name"=>array(array("field"=>"field1","order"="desc"),array("field"=>"field2","order"="asc")))
	 */
	public static function indexs(){
		return array("state"=>array(array("field"=>"state","order"=>"asc"))
				,"etype"=>array(array("field"=>"etype","order"=>"asc"))
				,"eid"=>array(array("field"=>"eid","order"=>"desc")));
	}
}

?>