<?php

define("AsyncTaskStateNone",0);
define("AsyncTaskStateRunning",1);
define("AsyncTaskStateOK",2);
define("AsyncTaskStateError",3);

/**
 * 异步任务
 * @author zhanghailong
 *
 */
class DBAsyncTask extends DBEntity{
	
	/**
	 *　异步任务ID
	 * @var int
	 */
	public $atid;
	/**
	 * 配置文件
	 * @var String
	 */
	public $config;
	/**
	 * 任务类型
	 * @var String
	 */
	public $taskType;
	/**
	 * 任务类
	 * @var String
	 */
	public $taskClass;
	/**
	 * 任务数据
	 * @var String
	 */
	public $data;
	/**
	 * 队列名
	 * @var String
	 */
	public $rank;
	/**
	 * 任务状态
	 * @var AsyncTaskState
	 */
	public $state;
	/**
	 * 结果
	 * @var String
	 */
	public $results;
	/**
	 * 创建时间
	 * @var int
	 */
	public $createTime;
	
	public function __construct(){
		$this->state = AsyncTaskStateNone;
	}
	
	public static function primaryKeys(){
		return array("atid");
	}
	
	public static function autoIncrmentFields(){
		return array("atid");
	}
	
	public static function tableName(){
		return "hl_async_task";
	}
	
	public static function tableField($field){
		return $field;
	}
	
	public static function tableFieldType($field){
		if($field == "atid"){
			return "BIGINT NOT NULL";
		}
		if($field == "config"){
			return "VARCHAR(128) NULL";
		}
		if($field == "taskType"){
			return "VARCHAR(128) NULL";
		}
		if($field == "taskClass"){
			return "VARCHAR(128) NULL";
		}
		if($field == "data"){
			return "TEXT NULL";
		}
		if($field == "rank"){
			return "VARCHAR(32) NULL";
		}
		if($field == "state"){
			return "INT NULL";
		}
		if($field == "results"){
			return "VARCHAR(128) NULL";
		}
		if($field == "createTime"){
			return "INT(11) NULL";
		}
		return "VARCHAR(45) NULL";
	}
	
	public static function getStateTitle($state){
		if($state == AsyncTaskStateOK){
			return "OK";
		}
		if($state == AsyncTaskStateError){
			return "Error";
		}
		if($state == AsyncTaskStateRunning){
			return "Running";
		}
		return "Wait";
	}
}

?>