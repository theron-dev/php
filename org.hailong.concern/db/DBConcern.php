<?php

/**
 * 单向关注表
 * @author zhanghailong
 *
 */
class DBConcern extends DBEntity{
	
	/**
	 *　关注ID
	 * @var int
	 */
	public $cid;
	/**
	 * 主动 用户ID
	 * @var int
	 */
	public $uid;
	/**
	 * 被动　目标用户ID
	 * @var int
	 */
	public $tuid;
	/**
	 * 目标是否拒绝
	 * @var int
	 */
	public $tblock;
	/**
	 * 关系来源
	 * @var String
	 */
	public $source;
	/**
	* 是否删除的
	* @var boolean
	*/
	public $deleted;
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

	}
	
	public static function primaryKeys(){
		return array("cid");
	}
	
	public static function autoIncrmentFields(){
		return array("cid");
	}
	
	public static function tableName(){
		return "hl_concern";
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
		if($field == "tuid"){
			return "BIGINT NULL";
		}
		if($field == "tblock"){
			return "INT NULL";
		}
		if($field == "source"){
			return "VARCHAR(32) NULL";
		}
		if($field == "deleted"){
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