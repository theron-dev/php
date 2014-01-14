<?php

define("DBPublishDomainStateNone",0);
define("DBPublishDomainStateDisabled",100);

/**
 * 发布域
 * @author zhanghailong
 *
 */
class DBPublishDomain extends DBEntity{
	
	/**
	 * 发布域ID
	 * @var int
	 */
	public $pdid;
	/**
	 * 创建者ID
	 * @var int
	 */
	public $uid;
	/**
	 * 域名 唯一
	 * @var String
	 */
	public $domain;
	/**
	 * 标题
	 * @var String
	 */
	public $title;
	/**
	 * 说明
	 * @var String
	 */
	public $body;
	/**
	 * 状态
	 * @var int
	 */
	public $state;
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
		return array("pdid");
	}
	
	public static function autoIncrmentFields(){
		return array("pdid");
	}
	
	public static function tableName(){
		return "hl_publish_domain";
	}
	
	public static function tableField($field){
		return $field;
	}
	
	public static function tableFieldType($field){		
		if($field == "pdid"){
			return "BIGINT NOT NULL";
		}
		if($field == "uid"){
			return "BIGINT NULL";
		}
		if($field == "domain"){
			return "VARCHAR(256) NULL";
		}
		if($field == "title"){
			return "VARCHAR(256) NULL";
		}
		if($field == "body"){
			return "VARCHAR(512) NULL";
		}
		if($field == "state"){
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
	
	public static function indexs(){
		return array("domain"=>array(array("field"=>"domain","order"=>"asc")));
	}
}

?>