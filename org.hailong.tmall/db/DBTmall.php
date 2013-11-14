<?php

/**
 * tmall
 * @author zhanghailong
 *
 */
class DBTmall extends DBEntity{
	
	/**
	 * tmall id
	 * @var int
	 */
	public $tmid;
	/**
	 * itemId
	 * @var String
	 */
	public $itemId;
	/**
	 * trackId
	 * @var String
	 */
	public $trackId;
	/**
	 * shopId
	 * @var String
	 */
	public $shopId;
	/**
	 * cid
	 * @var String
	 */
	public $cid;
	
	/**
	 * details
	 * @var String
	 */
	public $details;
	
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
		return array("tmid");
	}
	
	public static function autoIncrmentFields(){
		return array("tmid");
	}
	
	public static function tableName(){
		return "hl_tmall";
	}
	
	public static function tableField($field){
		return $field;
	}
	
	public static function tableFieldType($field){
		if($field == "tmid"){
			return "BIGINT NOT NULL";
		}		
		if($field == "itemId"){
			return "VARCHAR(64) NULL";
		}
		if($field == "trackId"){
			return "VARCHAR(128) NULL";
		}
		if($field == "shopId"){
			return "VARCHAR(64) NULL";
		}
		if($field == "cid"){
			return "VARCHAR(64) NULL";
		}
		if($field == "details"){
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
	
	public function setDetails($details){
		$this->details = json_encode($details);
	}
	
	public function getDetails(){
		return json_decode($this->details,true);
	}
}

?>