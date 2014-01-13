<?php

/**
* 删除数据任务
* @author zhanghailong
*
*/
class PublishDataRemoveTask extends AuthTask{
	
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
	/**
	 * 发布数据ID
	 * @var int
	 */
	public $eid;
	
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

