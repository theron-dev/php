<?php


/**
 * 会议成员
 * @author zhanghailong
 *
 */
class DBMeetingMember extends DBEntity{
	
	/**
	*　会议成员ID
	* @var int
	*/
	public $tmid;
	/**
	 *　会议ID
	 * @var int
	 */
	public $tid;
	/**
	* 成员用户ID
	* @var int
	*/
	public $uid;
	/**
	 * 成员消息用户ID
	 * @var int
	 */
	public $muid;
	/**
	 * 屏蔽
	 * @var boolean
	 */
	public $black;
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
		return array("tmid");
	}
	
	public static function autoIncrmentFields(){
		return array("tmid");
	}
	
	public static function tableName(){
		return "hl_msg_meeting_member";
	}
	
	public static function tableField($field){
		return $field;
	}
	
	public static function tableFieldType($field){
		if($field == "tmid"){
			return "BIGINT NOT NULL";
		}
		if($field == "tid"){
			return "BIGINT NULL";
		}
		if($field == "uid"){
			return "BIGINT NULL";
		}
		if($field == "muid"){
			return "BIGINT NULL";
		}
		if($field == "black"){
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