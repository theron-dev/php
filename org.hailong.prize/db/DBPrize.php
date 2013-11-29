<?php

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
	 * 规则
	 * @var String
	 */
	public $rule;
	
	/**
	 * 周期
	 * @var int
	 */
	public $period;
	
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
		return "hl_prize";
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
		if($field == "title"){
			return "VARCHAR(256) NULL";
		}
		if($field == "body"){
			return "TEXT NULL";
		}
		if($field == "rule"){
			return "TEXT NULL";
		}
		if($field == "period"){
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

}

?>