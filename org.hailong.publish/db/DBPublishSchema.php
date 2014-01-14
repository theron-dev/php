<?php

define("DBPublishSchemaStateNone",0);
define("DBPublishSchemaStatePublished",1);
define("DBPublishSchemaStateDisabled",100);

/**
 * 发布结构
 * @author zhanghailong
 *
 */
class DBPublishSchema extends DBEntity{
	
	/**
	 * 发布结构ID
	 * @var int
	 */
	public $psid;
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
	 * 路径 路径+版本号 唯一
	 * @var String
	 */
	public $path;
	/**
	* 版本号
	* @var String
	*/
	public $version;
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
	 * 结构描述
	 * @var String
	 */
	public $content;
	/**
	 * 发布时间
	 * @var int
	 */
	public $publishTime;
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
		return array("psid");
	}
	
	public static function autoIncrmentFields(){
		return array("psid");
	}
	
	public static function tableName(){
		return "hl_publish_schema";
	}
	
	public static function tableField($field){
		return $field;
	}
	
	public static function tableFieldType($field){		
		if($field == "psid"){
			return "BIGINT NOT NULL";
		}
		if($field == "pdid"){
			return "BIGINT NULL";
		}
		if($field == "uid"){
			return "BIGINT NULL";
		}
		if($field == "path"){
			return "VARCHAR(64) NULL";
		}
		if($field == "version"){
			return "VARCHAR(32) NULL";
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
		if($field == "content"){
			return "TEXT NULL";
		}
		if($field == "publishTime"){
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
		return array("path"=>array(array("field"=>"path","order"=>"asc")),"version"=>array(array("field"=>"version","order"=>"asc")),"pdid"=>array(array("field"=>"pdid","order"=>"asc")));
	}
}

?>