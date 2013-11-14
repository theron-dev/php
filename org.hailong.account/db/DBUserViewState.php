<?php


class DBUserViewState extends DBEntity{
	
	public $uvid;
	public $uid;
	public $session;
	public $saveSource;
	public $saveTime;
	public $alias;
	public $data;
	public $updateTime;
	public $createTime;
	
	
	public static function primaryKeys(){
		return array("uvid");
	}
	
	public static function autoIncrmentFields(){
		return array("uvid");
	}
	
	public static function tableName(){
		return "hl_user_view_state";
	}
	
	public static function tableField($field){
		return $field;
	}
	
	public static function tableFieldType($field){		
		if($field == "uvid"){
			return "BIGINT NOT NULL";
		}
		if($field == 'uid'){
			return "BIGINT NULL";
		}
		if($field == 'session'){
			return "VARCHAR(256) NULL";
		}
		if($field == 'saveSource'){
			return "VARCHAR(64) NULL";
		}
		if($field == 'saveTime'){
			return "INT NULL";
		}
		if($field == "alias"){
			return "VARCHAR(64) NULL";
		}
		if($field == "data"){
			return "TEXT NULL";
		}
		if($field == "updateTime"){
			return "INT(11) NULL";
		}
		if($field == "createTime"){
			return "INT(11) NULL";
		}
		return "VARCHAR(45) NULL";
	}
	
	public static function indexs(){
		return array("hl_user_view_state_uid"=>array(array("field"=>"uid","order"=>"asc"))
		,"hl_user_view_state_session"=>array(array("field"=>"session","order"=>"asc")));
	}
}

?>