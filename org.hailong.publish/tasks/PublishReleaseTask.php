<?php

/**
* 发布任务
* @author zhanghailong
*
*/
class PublishReleaseTask extends AuthTask{
	
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
	 * 沙盒子
	 * @var boolean
	 */
	public $sandbox;
	
	public function __construct($target=null,$sandbox=false){
		parent::__construct();
		
		$this->target = $target;
		$this->sandbox = $sandbox;
		
		if($sandbox){
			$this->dbKey = "publish/sandbox";
		}
		else{
			$this->dbKey = "publish/runtime";
		}
	}
}

?>

