<?php

define("DBPublishSchemaEntityTypeNone",0);
define("DBPublishSchemaEntityTypeHtml",1);
define("DBPublishSchemaEntityTypeXml",2);

/**
 * 发布实体结构
 * @author zhanghailong
 *
 */
class DBPublishSchemaEntity extends DBEntity{
	
	/**
	 * 发布实体结构ID
	 * @var int
	 */
	public $pseid;
	/**
	 * 发布域ID
	 * @var int
	 */
	public $pdid;
	/**
	 * 发布结构ID
	 * @var int
	 */
	public $psid;
	/**
	 * 创建者ID
	 * @var int
	 */
	public $uid;
	/**
	 * 实体名
	 * @var String
	 */
	public $name;
	/**
	 * 实体类型
	 * @var DBPublishSchemaEntityType
	 */
	public $entityType;
	/**
	 * 结构描述 xslt
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
		return array("pseid");
	}
	
	public static function autoIncrmentFields(){
		return array("pseid");
	}
	
	public static function tableName(){
		return "hl_publish_schema_entity";
	}
	
	public static function tableField($field){
		return $field;
	}
	
	public static function tableFieldType($field){		
		if($field == "pseid"){
			return "BIGINT NOT NULL";
		}
		if($field == "psid"){
			return "BIGINT NULL";
		}
		if($field == "pdid"){
			return "BIGINT NULL";
		}
		if($field == "uid"){
			return "BIGINT NULL";
		}
		if($field == "name"){
			return "VARCHAR(64) NULL";
		}
		if($field == "entityType"){
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
		return array("psid"=>array(array("field"=>"psid","order"=>"asc")),"pdid"=>array(array("field"=>"pdid","order"=>"asc")));
	}
}

?>