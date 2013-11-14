<?php


/**
 * 权限实体
 * @author zhanghailong
 *
 */
class DBAuthorityEntity extends DBEntity{
	
	/**
	 * 权限实体ID
	 * @var int
	 */
	public $aeid;
	/**
	 * 权限实体标示
	 * @var String
	 */
	public $alias;
	/**
	 * 标题
	 * @var String
	 */
	public $title;
	/**
	 * 创建时间
	 * @var int
	 */
	public $createTime;
	
	public static function primaryKeys(){
		return array("aeid");
	}
	
	public static function autoIncrmentFields(){
		return array("aeid");
	}
	
	public static function tableName(){
		return "hl_authority_entity";
	}
	
	public static function tableField($field){
		return $field;
	}
	
	public static function tableFieldType($field){
		if($field == "aeid"){
			return "BIGINT NOT NULL";
		}
		if($field == "alias"){
			return "VARCHAR(256) NULL";
		}
		if($field == "title"){
			return "VARCHAR(64) NULL";
		}
		if($field == "createTime"){
			return "INT(11) NULL";
		}
		return "VARCHAR(45) NULL";
	}
	
	public static function defaultEntitys(){
		$rs = array();
		
		$entity = new DBAuthorityEntity();
		$entity->alias = "org/hailong/authority/admin";
		$entity->createTime = time();
		
		$rs[] = $entity;
		
		
		$entity = new DBAuthorityEntity();
		$entity->alias = "workspace/admin/user";
		$entity->createTime = time();
		
		$rs[] = $entity;
		
		return $rs;
	}
}

?>