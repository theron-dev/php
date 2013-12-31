<?php

define("DBAuthorityTargetTypeNone",0);
define("DBAuthorityTargetTypeUser",1);
define("DBAuthorityTargetTypeRole",2);

/**
 * 权限
 * @author zhanghailong
 *
 */
class DBAuthority extends DBEntity{
	
	/**
	 * 权限ID
	 * @var int
	 */
	public $aid;
	/**
	 * 目标ID
	 * @var int
	 */
	public $tid; 
	
	/**
	 * 目标类型
	 * @var DBAuthorityTargetType
	 */
	public $ttype;
	/**
	 * 角色ID
	 * @var int
	 */
	public $arid;
	/**
	 * 实体ID
	 * @var int
	 */
	public $aeid;
	/**
	 * 创建时间
	 * @var int
	 */
	public $createTime;
	
	public static function primaryKeys(){
		return array("aid");
	}
	
	public static function autoIncrmentFields(){
		return array("aid");
	}
	
	public static function tableName(){
		return "hl_authority";
	}
	
	public static function tableField($field){
		return $field;
	}
	
	public static function tableFieldType($field){
		if($field == "aid"){
			return "BIGINT NOT NULL";
		}
		if($field == "tid"){
			return "BIGINT NULL";
		}
		if($field == "ttype"){
			return "INT NULL";
		}
		if($field == "arid"){
			return "BIGINT NULL";
		}
		if($field == "aeid"){
			return "BIGINT NULL";
		}
		if($field == "createTime"){
			return "INT(11) NULL";
		}
		return "VARCHAR(45) NULL";
	}
	
	public static function defaultEntitys(){
		$auth1 = new DBAuthority();
		$auth1->tid = 1;
		$auth1->ttype = DBAuthorityTargetTypeRole;
		$auth1->aeid = 1;
		$auth1->createTime = time();
		
		$auth2 = new DBAuthority();
		$auth2->tid = 1;
		$auth2->ttype = DBAuthorityTargetTypeUser;
		$auth2->arid = 1;
		$auth2->createTime = time();
		
		$auth3 = new DBAuthority();
		$auth3->tid = 1;
		$auth3->ttype = DBAuthorityTargetTypeRole;
		$auth3->aeid = 2;
		$auth3->createTime = time();
		return array($auth1,$auth2,$auth3);
	}
	
}

?>