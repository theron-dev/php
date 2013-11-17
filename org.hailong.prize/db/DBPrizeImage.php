<?php

/**
 * 奖品图片
 * @author zhanghailong
 *
 */
class DBPrizeImage extends DBEntity{
	
	/**
	 * 奖品图片ID
	 * @var int
	 */
	public $piid;
	
	/**
	 * 用户ID
	 * @var int
	 */
	public $uid;
	
	/**
	 * 发布ID
	 * @var int
	 */
	public $pid;
	
	/**
	 * 标题
	 * @var String
	 */
	public $title;
	
	/**
	 * 资源URI
	 * @var String
	 */
	public $uri;
	/**
	 * 宽度
	 * @var int
	 */
	public $width;
	/**
	 * 高度
	 * @var int
	 */
	public $height;
	
	
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
		return array("piid");
	}
	
	public static function autoIncrmentFields(){
		return array("piid");
	}
	
	public static function tableName(){
		return "hl_prize_image";
	}
	
	public static function tableField($field){
		return $field;
	}
	
	public static function tableFieldType($field){		
		if($field == "piid"){
			return "BIGINT NOT NULL";
		}
		if($field == "uid"){
			return "BIGINT NULL";
		}
		if($field == "pid"){
			return "BIGINT NULL";
		}
		if($field == "title"){
			return "VARCHAR(128) NULL";
		}		
		if($field == "uri"){
			return "VARCHAR(128) NULL";
		}
		if($field == "width"){
			return "INT NULL";
		}
		if($field == "height"){
			return "INT NULL";
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
				,"pid"=>array(array("field"=>"pid","order"=>"asc"))
				);
	}
}

?>