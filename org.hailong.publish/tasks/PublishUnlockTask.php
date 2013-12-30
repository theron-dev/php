<?php

/**
* 发布解锁任务
* @author zhanghailong
*
*/
class PublishUnlockTask extends AuthTask{
	
	/**
	 * 发布目标
	 * domain
	 * domain/path
	 * domain/path/version
	 * @var String
	 */
	public $target;
	
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
			$this->dir = "$library/org.hailong.publish/sandbox";
		}
		else{
			$this->dir = "$library/org.hailong.publish/runtime";
		}
	}
}

?>

