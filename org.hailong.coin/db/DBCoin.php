<?php

/**
 * 金币
 * @author zhanghailong
 *
 */
class DBCoin extends DBEntity{
	
	/**
	 *　金币ID
	 * @var int
	 */
	public $cid;
	/**
	 * 用户ID
	 * @var int
	 */
	public $uid;
	/**
	 * 当前金币
	 * @var double
	 */
	public $coin;
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
		return array("cid");
	}
	
	public static function autoIncrmentFields(){
		return array("cid");
	}
	
	public static function tableName(){
		return "hl_coin";
	}
	
	public static function tableField($field){
		return $field;
	}
	
	public static function tableFieldType($field){
		if($field == "cid"){
			return "BIGINT NOT NULL";
		}
		if($field == "uid"){
			return "BIGINT NULL";
		}
		if($field == "coin"){
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
	
	public function __construct(){
		$this->coin = 0.0;
		$this->updateTime = $this->createTime = time();
	}
}

?>