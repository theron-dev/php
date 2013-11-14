<?php

define("AuthorityRoleAdmin","Admin");
define("AuthorityRoleAnonymous","Anonymous");

/**
 * 权限角色
 * @author zhanghailong
 *
 */
class DBAuthorityRole extends DBEntity{
	
	/**
	 * 角色ID
	 * @var int
	 */
	public $arid;
	/**
	 * 角色名
	 * @var String
	 */
	public $name; 
	/**
	 * 标题
	 * @var String
	 */
	public $title;
	/**
	* 说明
	* @var String
	*/
	public $description;
	/**
	 * 创建时间
	 * @var int
	 */
	public $createTime;
	
	public static function primaryKeys(){
		return array("arid");
	}
	
	public static function autoIncrmentFields(){
		return array("arid");
	}
	
	public static function tableName(){
		return "hl_authority_role";
	}
	
	public static function tableField($field){
		return $field;
	}
	
	public static function tableFieldType($field){
		if($field == "arid"){
			return "BIGINT NOT NULL";
		}
		if($field == "name"){
			return "VARCHAR(32) NULL";
		}
		if($field == "title"){
			return "VARCHAR(64) NULL";
		}
		if($field == "description"){
			return "VARCHAR(128) NULL";
		}
		if($field == "createTime"){
			return "INT(11) NULL";
		}
		return "VARCHAR(45) NULL";
	}
	
	public static function defaultEntitys(){
		$admin = new DBAuthorityRole();
		$admin->name = AuthorityRoleAdmin;
		$admin->title = "管理员组";
		$admin->description = "管理员组";
		$admin->createTime = time();
		$anonymous = new DBAuthorityRole();
		$anonymous->name = AuthorityRoleAnonymous;
		$anonymous->title = "匿名用户组";
		$anonymous->description = "匿名用户组";
		$anonymous->createTime = time();
		return array($anonymous,$admin);
	}
}

?>