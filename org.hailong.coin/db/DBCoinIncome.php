<?php

/**
 * 金币收支记录
 * @author zhanghailong
 *
 */
class DBCoinIncome extends DBEntity{
	
	/**
	 * 记录ID
	 * @var int
	 */
	public $ciid;
	/**
	 * 用户ID
	 * @var int
	 */
	public $uid;
	/**
	 * 收支金币
	 * @var double
	 */
	public $coin;
	/**
	 * 来源
	 * @var String
	 */
	public $source;
	/**
	 * 来源ID
	 * @var int
	 */
	public $sid;
	/**
	 * 来源类型
	 * @var int
	 */
	public $stype;
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
		return array("ciid");
	}
	
	public static function autoIncrmentFields(){
		return array("ciid");
	}
	
	public static function tableName(){
		return "hl_coin_income";
	}
	
	public static function tableField($field){
		return $field;
	}
	
	public static function tableFieldType($field){
		if($field == "ciid"){
			return "BIGINT NOT NULL";
		}
		if($field == "uid"){
			return "BIGINT NULL";
		}
		if($field == "coin"){
			return "DOUBLE NULL";
		}
		if($field == "source"){
			return "VARCHAR(64) NULL";
		}
		if($field == "sid"){
			return "BIGINT NULL";
		}
		if($field == "stype"){
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
	
	public function __construct(){
		$this->coin = 0.0;
		$this->updateTime = $this->createTime = time();
	}
}

?>