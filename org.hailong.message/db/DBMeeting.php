<?php

/**
 * 会议
 * @author zhanghailong
 *
 */
class DBMeeting extends DBEntity{
	
	/**
	 *　会议ID
	 * @var int
	 */
	public $tid;
	/**
	* 发起者用户ID
	* @var int
	*/
	public $uid;
	/**
	 * 发起者消息用户ID
	 * @var int
	 */
	public $muid;
	/**
	 * 会议logo
	 * @var String
	 */
	public $logo;
	/**
	 * 标题
	 * @var String
	 */
	public $title;
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
		return "hl_msg_meeting";
	}
	
	public static function tableField($field){
		return $field;
	}
	
	public static function tableFieldType($field){
		if($field == "tid"){
			return "BIGINT NOT NULL";
		}
		if($field == "muid"){
			return "BIGINT NULL";
		}
		if($field == "uid"){
			return "BIGINT NULL";
		}
		if($field == "logo"){
			return "VARCHAR(128) NULL";
		}
		if($field == "title"){
			return "VARCHAR(128) NULL";
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