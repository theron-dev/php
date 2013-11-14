<?php

define("DBApplePurchaseStateNone",0);
define("DBApplePurchaseStatePurchased",1);
define("DBApplePurchaseStateFailed",2);

/**
 * 支付记录
 * @author zhanghailong
 *
 */
class DBApplePurchase extends DBEntity{
	
	/**
	 * 支付记录ID
	 * @var int
	 */
	public $pid;
	/**
	 * 用户ID
	 * @var int
	 */
	public $uid;
	/**
	 * 状态
	 * @var int
	 */
	public $state;
	
	/**
	 * 商品
	 * @var String
	 */
	public $product;
	/**
	 * 事务
	 * @var String
	 */
	public $transaction;
	/**
	 * 验证数据
	 * @var String
	 */
	public $receipt;
	/**
	 * 结果
	 * @var String
	 */
	public $results;
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
	
	public function __construct(){
		$this->updateTime = time();
		$this->createTime = time();
	}
	
	public static function primaryKeys(){
		return array("pid");
	}
	
	public static function autoIncrmentFields(){
		return array("pid");
	}
	
	public static function tableName(){
		return "hl_apple_purchase";
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
		if($field == "state"){
			return "INT NULL";
		}
		if($field == 'product'){
			return "VARCHAR(256) NULL";
		}
		if($field == 'transaction'){
			return "VARCHAR(128) NULL";
		}
		if($field == 'receipt'){
			return "TEXT NULL";
		}
		if($field == 'results'){
			return "TEXT NULL";
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