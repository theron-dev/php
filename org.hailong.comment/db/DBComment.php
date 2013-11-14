<?php

define("DBCommentTypeNone",0);
define("DBCommentTypeShare",1);

define("DBCommentSourceNone",0);

/**
 * 评论表
 * @author zhanghailong
 *
 */
class DBComment extends DBEntity{
	
	/**
	 * 评论ID
	 * @var int
	 */
	public $cid;
	/**
	 * 父级评论ID
	 * @var int
	 */
	public $pcid;
	/**
	 * 用户ID 发起者
	 * @var int
	 */
	public $uid;
	/**
	 * 内容
	 * @var String
	 */
	public $body;
	/**
	 * 目标类型
	 * @var int
	 */
	public $etype;
	/**
	 * 目标ID
	 * @var int
	 */
	public $eid;
	/**
	 * 来源
	 * @var int
	 */
	public $source;
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
		return "hl_comment";
	}
	
	public static function tableField($field){
		return $field;
	}
	
	public static function tableFieldType($field){		
	
		if($field == "cid"){
			return "BIGINT NOT NULL";
		}
		if($field == "pcid"){
			return "BIGINT NULL";
		}
		if($field == "uid"){
			return "BIGINT NULL";
		}
		if($field == "body"){
			return "VARCHAR(512) NULL";
		}
		if($field == "etype"){
			return "INT NULL";
		}
		if($field == "eid"){
			return "BIGINT NULL";
		}
		if($field == "source"){
			return "INT(4) NULL";
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