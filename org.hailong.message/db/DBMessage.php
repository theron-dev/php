<?php

define("MessageTypeNone",0);
define("MessageTypeRich",1);
define("MessageTypeHtml",2);
define("MessageTypeApp",3);
define("MessageTypeUrl",4);
define("MessageTypeNotice",5);

define("MessageStateNone",0);
define("MessageStateReach",1);	// 到达对方
define("MessageStateRead",2);	// 对方已读
define("MessageStateOK",4);		// 发方确认

/**
 * 消息
 * @author zhanghailong
 *
 */
class DBMessage extends DBEntity{
	
	/**
	 *　消息ID
	 * @var int
	 */
	public $mid;
	/**
	 * 消息类型
	 * @var int
	 */
	public $mtype;
	/**
	 * 消息状态
	 * @var int
	 */
	public $mstate;
	/**
	 * 发起者, -1 为系统
	 * @var int
	 */
	public $uid;
	/**
	 * 目标会议ID
	 * @var int
	 */
	public $tid;
	/**
	 * 目标用户ID
	 * @var int
	 */
	public $tuid;
	/**
	* 目标消息用户ID
	* @var int
	*/
	public $tmuid;
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
	 * 是否有附件
	 * @var boolean
	 */
	public $hasAttach;
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
		$this->mtype = MessageTypeRich;
	}
	
	public static function primaryKeys(){
		return array("mid");
	}
	
	public static function autoIncrmentFields(){
		return array("mid");
	}
	
	public static function tableName(){
		return "hl_msg_message";
	}
	
	public static function tableField($field){
		return $field;
	}
	
	public static function tableFieldType($field){
		if($field == "mid"){
			return "BIGINT NOT NULL";
		}
		if($field == "mtype"){
			return "INT NULL";
		}
		if($field == "mstate"){
			return "INT NULL";
		}
		if($field == "uid"){
			return "BIGINT NULL";
		}
		if($field == "tid"){
			return "BIGINT NULL";
		}
		if($field == "tuid"){
			return "BIGINT NULL";
		}
		if($field == "tmuid"){
			return "BIGINT NULL";
		}
		if($field == "title"){
			return "VARCHAR(128) NULL";
		}
		if($field == "body"){
			return "TEXT NULL";
		}
		if($field == "hasAttach"){
			return "INT NULL";
		}
		if($field == "updateTime"){
			return "INT NULL";
		}
		if($field == "createTime"){
			return "INT NULL";
		}
		return "VARCHAR(45) NULL";
	}
	
	
	
}

?>