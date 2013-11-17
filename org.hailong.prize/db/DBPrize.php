<?php

define("DBPrizeStateNone",0);
define("DBPrizeStateSell",1);

/**
 * 奖品
 * @author zhanghailong
 *
 */
class DBPrize extends DBEntity{
	
	/**
	 * 奖品ID
	 * @var int
	 */
	public $pid;
	
	/**
	 * 用户ID
	 * @var int
	 */
	public $uid;
	
	/**
	 * 标题
	 * @var String
	 */
	public $title;
	
	/**
	 * 内容
	 * @var String
	 */
	public $body;
	
	/**
	 * 图片
	 * @var String
	 */
	public $image;
	
	/**
	 * 积分
	 * @var double
	 */
	public $integral;
	
	/**
	 * 数量
	 * @var int
	 */
	public $count;
	
	
	/**
	 * 喜欢数
	 * @var int
	 */
	public $likedCount;
	
	/**
	 * 评论数
	 * @var int
	 */
	public $commentCount;
		
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
		return "qdd_publish";
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
		if($field == "body"){
			return "TEXT NULL";
		}	
		if($field == "likedCount"){
			return "INT NULL";
		}
		if($field == "commentCount"){
			return "INT NULL";
		}
		if($field == "payMoney"){
			return "DOUBLE NULL";
		}
		if($field == "expendMoney"){
			return "DOUBLE NULL";
		}
		if($field == "latitude"){
			return "DOUBLE NULL";
		}
		if($field == "longitude"){
			return "DOUBLE NULL";
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
				,"createTime"=>array(array("field"=>"createTime","order"=>"asc"))
				,"latitude"=>array(array("field"=>"latitude","order"=>"asc"))
				,"longitude"=>array(array("field"=>"longitude","order"=>"asc")));
	}
	
}

?>