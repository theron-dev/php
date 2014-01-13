<?php

/**
* 添加数据任务
* @author zhanghailong
*
*/
class PublishDataAddTask extends AuthTask{
	
	/**
	 * 发布目标
	 * domain/path/version
	 * @var String
	 */
	public $target;
	/**
	 * @var any
	 */
	public $data;
	/**
	 * 时间戳
	 * @var int
	 */
	public $timestamp;
	/**
	 * 发布数据库Key
	 * @var String
	 */
	public $dbKey;
	
	public function __construct($target=null,$sandbox=false){
		parent::__construct();
		
		$this->target = $target;
	
		if($sandbox){
			$this->dbKey = "publish/sandbox";
		}
		else{
			$this->dbKey = "publish/runtime";
		}
	}
}

?>

