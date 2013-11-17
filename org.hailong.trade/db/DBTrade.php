<?php

define("DBTradeTypeNone",0);
define("DBTradeTypeCoin",1);
define("DBTradeTypeMoney",2);

define("DBTradeStateNone",0);
define("DBTradeStatePaid",1);
define("DBTradeStateShipped",2);
define("DBTradeStateConfirm",3);
define("DBTradeStateCommented",4);
define("DBTradeStateClosed",5);
define("DBTradeStateCanceled",6);

define("DBTradeRefundStateNone",0);
define("DBTradeRefundStateCommit",1);
define("DBTradeRefundStateCanceled",2);
define("DBTradeRefundStateClosed",3);

/**
 * 交易
 * @author zhanghailong
 *
 */
class DBTrade extends DBEntity{
	
	/**
	 *　交易ID
	 * @var int
	 */
	public $tid;
	/**
	 * 类型
	 * @var int
	 */
	public $type;
	/**
	 * 状态
	 * @var int
	 */
	public $state;
	/**
	 * 用户ID
	 * @var int
	 */
	public $uid;
	/**
	 * 商品所有者ID
	 * @var int
	 */
	public $puid;
	/**
	 * 商品ID
	 * @var int
	 */
	public $pid;
	/**
	 * 单价
	 * @var float
	 */
	public $unitPrice;
	/**
	 * 数量
	 * @var int
	 */
	public $count;
	/**
	 * 减价
	 * @var float
	 */
	public $reduce;
	/**
	 * 退款
	 * @var float
	 */
	public $refundPrice;
	/**
	* 退款状态
	* @var int
	*/
	public $refundState;
	/**
	 * 内容 json
	 * @var Object
	 */
	public $body;
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
		return "hl_trade";
	}
	
	public static function tableField($field){
		return $field;
	}
	
	public static function tableFieldType($field){
		
		if($field == "tid"){
			return "BIGINT NOT NULL";
		}
		if($field == "uid"){
			return "BIGINT NULL";
		}
		if($field == "puid"){
			return "BIGINT NULL";
		}
		if($field == "type"){
			return "INT NULL";
		}
		if($field == "state"){
			return "INT NULL";
		}
		if($field == "pid"){
			return "BIGINT NULL";
		}
		if($field == "count"){
			return "INT NULL";
		}
		if($field == "unitPrice"){
			return "DOUBLE NULL";
		}
		if($field == "reduce"){
			return "DOUBLE NULL";
		}
		if($field == "refundPrice"){
			return "DOUBLE NULL";
		}
		if($field == "refundState"){
			return "INT NULL";
		}
		if($field == "body"){
			return "TEXT NULL";
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
		$this->type = DBTradeTypeCoin;
		$this->state = DBTradeStateNone;
		$this->refundState = DBTradeRefundStateNone;
		$this->reduce = 0.0;
		$this->updateTime = $this->createTime = time();
	}
}

?>