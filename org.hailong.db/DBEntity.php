<?php
/**
 * 数据实体，表结构
 */

class DBEntity{
	
	public static function primaryKeys(){
		return array();
	}
	
	public static function autoIncrmentFields(){
		return array();
	}
	
	public static function tableName(){
		return "";
	}
	
	public static function tableField($field){
		return $field;
	}
	
	public static function tableFieldType($field){
		return "VARCHAR(45) NULL";
	}

	public static function defaultEntitys(){
		return array();
	}
	
	/**
	 * @return array("index_name"=>array(array("field"=>"field1","order"="desc"),array("field"=>"field2","order"="asc")))
	 */
	public static function indexs(){
		return array();
	}
}

?>