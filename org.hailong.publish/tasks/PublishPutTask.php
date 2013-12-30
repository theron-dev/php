<?php

/**
* 执行发布任务
* @author zhanghailong
*
*/
class PublishPutTask extends AuthTask{
	
	/**
	 * 发布目标
	 * domain/path/version
	 * @var String
	 */
	public $target;
	/**
	 * 发布数据源
	 * @var IPublishDataSource
	 */
	public $source;
	/**
	 * 发布数据库Key
	 * @var String
	 */
	public $dbKey;
	
	/**
	 * 发布目录
	 * @var String
	 */
	public $dir;
	
	
	public function __construct($target=null,$sandbox=false){
		parent::__construct();
		
		$this->target = $target;
		
		global $library;
		
		if($sandbox){
			$this->dbKey = "publish/sandbox";
			$this->dir = "$library/org.hailong.publish/sandbox";
		}
		else{
			$this->dbKey = "publish/runtime";
			$this->dir = "$library/org.hailong.publish/runtime";
		}
	}
}

?>

