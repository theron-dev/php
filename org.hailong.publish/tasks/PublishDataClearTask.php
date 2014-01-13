<?php

/**
* 清除数据任务
* @author zhanghailong
*
*/
class PublishDataClearTask extends AuthTask{
	
	/**
	 * 发布目标
	 * domain/path/version
	 * @var String
	 */
	public $target;
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

