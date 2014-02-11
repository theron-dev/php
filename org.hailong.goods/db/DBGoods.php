<?php


define("DBGoodsExternTypeNone",0);
define("DBGoodsExternTypeTaobao",1);
define("DBGoodsExternTypeTmall",2);
define("DBGoodsExternTypeTaoke",3);

define("DBGoodsUnitNone",0);

/**
 * 物品表
 * @author zhanghailong
 *
 */
class DBGoods extends DBEntity{
	
	/**
	 * 物品ID
	 * @var int
	 */
	public $gid;
	/**
	 * 用户ID
	 * @var int
	 */
	public $uid;
	/**
	 * 外部物品类型
	 * @var String
	 */
	public $etype;
	/**
	 * 外部物品ID
	 * @var int
	 */
	public $eid;
	/**
	 * 推广ID
	 * @var int
	 */
	public $sid;
	/**
	 * 标题
	 * @var String
	 */
	public $title;
	/**
	 * 描述
	 * @var String
	 */
	public $body;
	/**
	 * 图片
	 * @var String
	 */
	public $image;
	/**
	 * 价格
	 * @var double
	 */
	public $price;
	/**
	 * 单位
	 * @var int
	 */
	public $unit;
	/**
	 * 商品URL
	 * @var String
	 */
	public $url;
	/**
	 * 商品WAP URL
	 * @var String
	 */
	public $wapUrl;
	/**
	 * 来源
	 * @var String
	 */
	public $source;
	/**
	 * 喜欢数
	 * @var int
	 */
	public $likeCount;
	/**
	 * 转发数
	 * @var int
	 */
	public $forwardCount;
	/**
	 * 浏览数　
	 * @var int
	 */
	public $browseCount;
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
		return array("gid");
	}
	
	public static function autoIncrmentFields(){
		return array("gid");
	}
	
	public static function tableName(){
		return "hl_goods";
	}
	
	public static function tableField($field){
		return $field;
	}
	
	public static function tableFieldType($field){		
		
		if($field == "gid"){
			return "BIGINT NOT NULL";
		}
		if($field == "uid"){
			return "BIGINT NULL";
		}
		if($field == "etype"){
			return "INT NULL";
		}
		if($field == "eid"){
			return "BIGINT NULL";
		}
		if($field == "sid"){
			return "BIGINT NULL";
		}
		if($field == "title"){
			return "VARCHAR(256) NULL";
		}
		if($field == "body"){
			return "VARCHAR(512) NULL";
		}
		if($field == "image"){
			return "VARCHAR(256) NULL";
		}
		if($field == "price"){
			return "DOUBLE NULL";
		}
		if($field == "unit"){
			return "INT NULL";
		}
		if($field == "url"){
			return "VARCHAR(32) NULL";
		}
		if($field == "wapUrl"){
			return "VARCHAR(32) NULL";
		}
		if($field == "source"){
			return "VARCHAR(16) NULL";
		}
		if($field == "likeCount"){
			return "INT NULL";
		}
		if($field == "forwardCount"){
			return "INT NULL";
		}	
		if($field == "browseCount"){
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
	
	public static function extendTypeTitle($etype){
		switch($etype){
			case DBGoodsExternTypeTaobao:
				return "taobao";
			case DBGoodsExternTypeTmall:
				return "tmall";
			case DBGoodsExternTypeTaoke:
				return "taoke";
		}
		return false;
	}
}

?>