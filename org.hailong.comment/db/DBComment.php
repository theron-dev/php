<?php

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
	 * 用户ID 发起者
	 * @var int
	 */
	public $uid;
	/**
	 * 目标用户ID
	 * @var int
	 */
	public $tuid;
	/**
	 * 内容
	 * @var String
	 */
	public $body;
	/**
	 * 实体类型
	 * @var int
	 */
	public $etype;
	/**
	 * 实体ID
	 * @var int
	 */
	public $eid;
	/**
	 * 目标类型
	 * @var int
	 */
	public $ttype;
	/**
	 * 目标ID
	 * @var int
	 */
	public $tid;
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
		if($field == "uid"){
			return "BIGINT NULL";
		}
		if($field == "tuid"){
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
		if($field == "ttype"){
			return "INT NULL";
		}
		if($field == "tid"){
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
	
	/**
	 * @return array("index_name"=>array(array("field"=>"field1","order"="desc"),array("field"=>"field2","order"="asc")))
	 */
	public static function indexs(){
		return array("uid"=>array(array("field"=>"uid","order"=>"asc"))
				,"tuid"=>array(array("field"=>"tuid","order"=>"asc"))
				,"eid"=>array(array("field"=>"etype","order"=>"asc"),array("field"=>"eid","order"=>"asc"))
				,"tid"=>array(array("field"=>"ttype","order"=>"asc"),array("field"=>"tid","order"=>"asc"))
				
		);
	}
}

?>