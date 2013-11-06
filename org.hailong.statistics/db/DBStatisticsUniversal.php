<?php

/**
 * 通用统计表
 * @author zhanghailong
 *
 */
class DBStatisticsUniversal extends DBStatistics{
	
	/**
	 * 访问量
	 * @var int
	 */
	public $pv;
	
	public static function tableName(){
		return "hl_statistics_universal";
	}

	public static function tableFieldType($field){		
		if($field == "pv"){
			return "INT NULL";
		}
		return DBStatistics::tableFieldType($field);
	}
	
	public function __construct(){
		$this->pv = 0;
	}
}

?>